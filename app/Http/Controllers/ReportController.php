<?php

namespace App\Http\Controllers;

use App\Models\AidProgram;
use App\Models\Family;
use App\Models\FinancialManagement;
use App\Models\Report;
use App\Models\Surveys;
use App\Models\Volunteer;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['reports'] = Report::query()->latest()->get();
        $data['should_load_report_list1'] = false;
        $data['filters'] = [
            'type' => request('type'),
            'period' => request('period'),
        ];

        return view('report.report')->with($data);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'period' => ['required', 'string', 'max:100'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
        ], [
            'type.required' => 'Please select a report type.',
            'period.required' => 'Please select a report period.',
        ]);

        if (($validated['period'] ?? null) === 'Custom Range') {
            $request->validate([
                'from_date' => ['required', 'date'],
                'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            ], [
                'from_date.required' => 'Please select start date.',
                'to_date.required' => 'Please select end date.',
                'to_date.after_or_equal' => 'End date must be after or equal to start date.',
            ]);
        }

        $data['reports'] = Report::query()->latest()->get();
        $data['should_load_report_list1'] = true;
        $data['filters'] = [
            'type' => $validated['type'] ?? null,
            'period' => $validated['period'] ?? null,
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ];

        return view('report.report')->with($data);
    }

    public function export(Request $request)
    {
        $type = $request->query('type');
        $period = $request->query('period');
        $format = strtolower($request->query('format','csv'));
        $from = $request->query('from');
        $to = $request->query('to');

        $organizationId = optional($request->user())->organization_id ?? session('organization_id');
        $range = $this->normalizePeriod($period);
        $fromC = $from ? \Illuminate\Support\Carbon::parse($from) : ($range['from'] ?? null);
        $toC = $to ? \Illuminate\Support\Carbon::parse($to) : ($range['to'] ?? null);

        $filenameBase = 'report'.($type ? '-'.preg_replace('/\s+/', '-', strtolower($type)) : '').($period ? '-'.preg_replace('/\s+/', '-', strtolower($period)) : '').'-'.now()->format('Ymd_His');

        switch ($type) {
            case 'Financial':
                $q = FinancialManagement::with(['aidProgram','family','recordedBy','approvedBy'])
                    ->where('organization_id',$organizationId)->latest();
                if ($fromC && $toC) $q->whereBetween('date', [$fromC->toDateString(), $toC->toDateString()]);
                $rows = $q->get();
                $columns = ['S.No','Date','Type','Amount','Program','Family','Recorded By','Approved By'];
                $mapped = $rows->values()->map(function($r, $i){
                    return [
                        $i + 1,
                        optional($r->date)->format('Y-m-d'),
                        $r->transaction_type,
                        (float) $r->amount,
                        optional($r->aidProgram)->program_title,
                        optional($r->family)->family_code,
                        optional($r->recordedBy)->name,
                        optional($r->approvedBy)->name,
                    ];
                });
                $title = 'Financial Report';
                break;
            case 'Aid Programs':
                $q = AidProgram::where('organization_id',$organizationId)
                    ->withCount(['families','volunteers'])
                    ->with(['families:id,family_code,head_of_family_name,family_members_count','volunteers:id,full_name'])
                    ->latest();
                $rows = $q->get();
                $columns = ['S.No','Program','Status','Families','Volunteers','Members Total','Family Codes','Head Names','Volunteer Names','Created'];
                $mapped = $rows->values()->map(function($r, $i){
                    $familyCodes = collect($r->families)->pluck('family_code')->filter()->take(10)->join(', ');
                    $headNames = collect($r->families)->pluck('head_of_family_name')->filter()->take(10)->join(', ');
                    $volNames = collect($r->volunteers)->pluck('full_name')->filter()->take(10)->join(', ');
                    $membersTotal = (int) collect($r->families)->sum(function($f){ return (int) ($f->family_members_count ?? 0); });
                    return [
                        $i + 1,
                        $r->program_title,
                        $r->status,
                        (int) ($r->families_count ?? 0),
                        (int) ($r->volunteers_count ?? 0),
                        $membersTotal,
                        $familyCodes,
                        $headNames,
                        $volNames,
                        optional($r->created_at)->format('Y-m-d'),
                    ];
                });
                $title = 'Aid Programs Report';
                break;
            case 'Survey':
                $q = Surveys::where('organization_id',$organizationId)->with(['enumerator','family'])->latest();
                if ($fromC && $toC) $q->whereBetween('created_at', [$fromC->toDateTimeString(), $toC->toDateTimeString()]);
                $rows = $q->get();
                $columns = ['S.No','Code','Family','Enumerator','Members','Uploads','Created'];
                $mapped = $rows->values()->map(function($r, $i){
                    return [
                        $i + 1,
                        ($r->survey_code ?: ('S-'.$r->id)),
                        optional($r->family)->family_code,
                        optional($r->enumerator)->full_name,
                        (int) ($r->members_count ?? $r->members()->count()),
                        (int) ($r->uploads_count ?? $r->uploads()->count()),
                        optional($r->created_at)->format('Y-m-d'),
                    ];
                });
                $title = 'Survey Report';
                break;
            case 'Volunteers':
                $q = Volunteer::where('organization_id',$organizationId)->latest();
                $rows = $q->get();
                $columns = ['S.No','Name','Email','Contact','Joining Date','Status'];
                $mapped = $rows->values()->map(function($r, $i){
                    return [
                        $i + 1,
                        $r->full_name,
                        $r->email,
                        $r->contact_number,
                        optional($r->joining_date)->format('Y-m-d'),
                        $r->status,
                    ];
                });
                $title = 'Volunteers Report';
                break;
            case 'Families':
            default:
                $q = Family::where('organization_id',$organizationId)->with(['volunteer'])->latest();
                if ($fromC && $toC) $q->whereBetween('created_at', [$fromC->toDateTimeString(), $toC->toDateTimeString()]);
                $rows = $q->get();
                $columns = ['S.No','Family Code','Head','Members','Volunteer','Zakat Eligible','Created'];
                $mapped = $rows->values()->map(function($r, $i){
                    return [
                        $i + 1,
                        $r->family_code,
                        ($r->head_of_family_name ?? $r->family_head_name),
                        (int) ($r->members_count ?? $r->members()->count()),
                        optional($r->volunteer)->full_name,
                        ($r->zakat_eligibility ? 'Yes' : 'No'),
                        optional($r->created_at)->format('Y-m-d'),
                    ];
                });
                $title = 'Families Report';
                break;
        }

        if ($format === 'pdf') {
            $pdfData = [
                'title' => $title,
                'subtitle' => ($period ?: 'All Periods'),
                'columns' => $columns,
                'rows' => $mapped->toArray(),
            ];
            $filename = $filenameBase.'.pdf';
            return PDF::loadView('report.pdf.generic', $pdfData)->download($filename);
        }

        $filename = $filenameBase.'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($columns, $mapped, $type, $period) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Report Type', 'Period', 'Generated At']);
            fputcsv($handle, [$type ?: 'All', $period ?: 'All', now()->toDateTimeString()]);
            fputcsv($handle, $columns);
            foreach ($mapped as $row) { fputcsv($handle, $row); }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadAll()
    {
        $zip = new \ZipArchive;
        $tmpPath = storage_path('app/tmp');
        if (! is_dir($tmpPath)) {
            @mkdir($tmpPath, 0777, true);
        }
        $zipPath = $tmpPath.'/all-reports-'.now()->format('Ymd_His').'.zip';

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create ZIP');
        }

        $organizationId = optional(auth()->user())->organization_id ?? session('organization_id');

        // Families
        $families = Family::where('organization_id',$organizationId)->with(['volunteer'])->latest()->get();
        $zip->addFromString('families.csv', $this->toCsv(['S.No','Family Code','Head','Members','Volunteer','Zakat Eligible','Created'], $families->values()->map(function($r, $i){ return [
            $i + 1, $r->family_code, ($r->head_of_family_name ?? $r->family_head_name), (int) ($r->members_count ?? $r->members()->count()), optional($r->volunteer)->full_name, ($r->zakat_eligibility ? 'Yes' : 'No'), optional($r->created_at)->format('Y-m-d')
        ]; })->toArray()));

        // Aid Programs
        $programs = AidProgram::where('organization_id',$organizationId)->withCount(['families','volunteers'])->latest()->get();
        $zip->addFromString('aid-programs.csv', $this->toCsv(['S.No','Program','Status','Families','Volunteers','Created'], $programs->values()->map(function($r, $i){ return [
            $i + 1, $r->program_title, $r->status, (int) ($r->families_count ?? 0), (int) ($r->volunteers_count ?? 0), optional($r->created_at)->format('Y-m-d')
        ]; })->toArray()));

        // Financial
        $financial = FinancialManagement::where('organization_id',$organizationId)->with(['aidProgram','family','recordedBy','approvedBy'])->latest()->get();
        $zip->addFromString('financial.csv', $this->toCsv(['S.No','Date','Type','Amount','Program','Family','Recorded By','Approved By'], $financial->values()->map(function($r, $i){ return [
            $i + 1, optional($r->date)->format('Y-m-d'), $r->transaction_type, (float) $r->amount, optional($r->aidProgram)->program_title, optional($r->family)->family_code, optional($r->recordedBy)->name, optional($r->approvedBy)->name
        ]; })->toArray()));

        // Survey
        $surveys = Surveys::where('organization_id',$organizationId)->with(['enumerator','family'])->latest()->get();
        $zip->addFromString('surveys.csv', $this->toCsv(['S.No','Code','Family','Enumerator','Members','Uploads','Created'], $surveys->values()->map(function($r, $i){ return [
            $i + 1, ($r->survey_code ?: ('S-'.$r->id)), optional($r->family)->family_code, optional($r->enumerator)->full_name, (int) ($r->members_count ?? $r->members()->count()), (int) ($r->uploads_count ?? $r->uploads()->count()), optional($r->created_at)->format('Y-m-d')
        ]; })->toArray()));

        // Volunteers
        $vols = Volunteer::where('organization_id',$organizationId)->latest()->get();
        $zip->addFromString('volunteers.csv', $this->toCsv(['S.No','Name','Email','Contact','Joining Date','Status'], $vols->values()->map(function($r, $i){ return [
            $i + 1, $r->full_name, $r->email, $r->contact_number, optional($r->joining_date)->format('Y-m-d'), $r->status
        ]; })->toArray()));

        // Summary PDF with organization branding
        $pdfData = [
            'title' => 'All Reports Summary',
            'subtitle' => 'Generated '.now()->format('Y-m-d H:i'),
            'columns' => ['Section','Count'],
            'rows' => [
                ['Families', count($families)],
                ['Aid Programs', count($programs)],
                ['Financial Records', count($financial)],
                ['Surveys', count($surveys)],
                ['Volunteers', count($vols)],
            ],
        ];
        $pdf = PDF::loadView('report.pdf.generic', $pdfData);
        $pdfPath = $tmpPath.'/summary-'.now()->format('Ymd_His').'.pdf';
        @file_put_contents($pdfPath, $pdf->output());
        if (file_exists($pdfPath)) {
            $zip->addFile($pdfPath, 'summary.pdf');
        }

        $zip->close();

        $response = response()->download($zipPath, basename($zipPath), [
            'Content-Type' => 'application/zip'
        ])->deleteFileAfterSend(true);

        // Optionally cleanup temp pdf asynchronously
        try { if (file_exists($pdfPath)) { @unlink($pdfPath); } } catch (\Throwable $e) {}

        return $response;
    }

    public function view(Request $request)
    {
        $type = $request->query('type');
        $period = $request->query('period');
        $download = $request->query('download');

        $range = $this->normalizePeriod($period);
        $from = $request->query('from') ? \Illuminate\Support\Carbon::parse($request->query('from')) : ($range['from'] ?? null);
        $to = $request->query('to') ? \Illuminate\Support\Carbon::parse($request->query('to')) : ($range['to'] ?? null);

        $data = [
            'filters' => ['type' => $type, 'period' => $period],
            'type' => $type,
            'period' => $period,
        ];

        $organizationId = optional($request->user())->organization_id ?? session('organization_id');
        switch ($type) {
            case 'Financial':
                $q = FinancialManagement::with(['aidProgram', 'family', 'recordedBy', 'approvedBy'])
                    ->where('organization_id', $organizationId)
                    ->latest();
                if ($from && $to) {
                    $q->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
                }
                $data['rows'] = $q->paginate(20)->appends(['type' => $type, 'period' => $period]);
                $incomeTypes = ['Donation', 'Transfer'];
                $expenseTypes = ['Expense', 'Disbursement'];
                $incomeQ = FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $incomeTypes);
                $expenseQ = FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $expenseTypes);
                if ($from && $to) {
                    $incomeQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
                    $expenseQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
                }
                $totalIncome = (int) $incomeQ->sum('amount');
                $totalExpenses = (int) $expenseQ->sum('amount');
                $data['kpis'] = [
                    'total_income' => $totalIncome,
                    'total_expenses' => $totalExpenses,
                    'net_balance' => $totalIncome - $totalExpenses,
                    'pending_transactions' => FinancialManagement::where('organization_id', $organizationId)->whereNull('approved_by')->count(),
                ];

                if ($download === 'pdf') {
                    $rows = $q->get();
                    $pdfData = [
                        'title' => 'Financial Report',
                        'subtitle' => ($period ?: 'All Periods'),
                        'columns' => ['Date', 'Type', 'Amount', 'Program', 'Family', 'Recorded By', 'Approved By'],
                        'rows' => $rows->map(function ($r) {
                            return [
                                optional($r->date)->format('Y-m-d'),
                                $r->transaction_type,
                                number_format((float) $r->amount, 2),
                                optional($r->aidProgram)->name,
                                optional($r->family)->family_name,
                                optional($r->recordedBy)->name,
                                optional($r->approvedBy)->name,
                            ];
                        })->toArray(),
                    ];
                    $filename = 'financial-report-'.now()->format('Ymd_His').'.pdf';

                    return PDF::loadView('report.pdf.generic', $pdfData)->download($filename);
                }
                break;
            case 'Volunteers':
                $v = Volunteer::where('organization_id', $organizationId)->latest();
                $data['rows'] = $v->paginate(20)->appends(['type' => $type, 'period' => $period]);
                $data['kpis'] = [
                    'total_volunteers' => Volunteer::where('organization_id', $organizationId)->count(),
                    'active_this_month' => Volunteer::where('organization_id', $organizationId)->where('status', 'Active')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->count(),
                ];
                break;
            case 'Aid Programs':
                $p = AidProgram::where('organization_id', $organizationId)
                    ->withCount(['families', 'volunteers', 'attachments'])
                    ->with(['families:id,family_code,head_of_family_name,family_members_count', 'volunteers:id,full_name'])
                    ->withSum(['transactions as disbursed_amount' => function ($q) {
                        $q->where('transaction_type', 'Disbursement');
                    }], 'amount')
                    ->latest();
                $data['rows'] = $p->paginate(20)->appends(['type' => $type, 'period' => $period]);
                $data['kpis'] = [
                    'active_programs' => AidProgram::where('organization_id', $organizationId)->where('status', 'Active')->count(),
                    'total_budget_active' => (int) AidProgram::where('organization_id', $organizationId)->where('status', 'Active')->sum('total_budget'),
                    'beneficiaries_total' => (int) AidProgram::where('organization_id', $organizationId)->sum('target_beneficiaries'),
                ];
                break;
            case 'Survey':
                $q = Surveys::where('organization_id', $organizationId)->with(['enumerator', 'family'])->withCount(['members', 'uploads'])->latest();
                if ($from && $to) {
                    $q->whereBetween('created_at', [$from->toDateTimeString(), $to->toDateTimeString()]);
                }
                $data['rows'] = $q->paginate(20)->appends(['type' => $type, 'period' => $period]);
                $data['kpis'] = [
                    'total_surveys' => Surveys::where('organization_id', $organizationId)->count(),
                    'active_surveys' => Surveys::where('organization_id', $organizationId)->where('revisit_flag', true)->count(),
                    'completed_surveys' => Surveys::where('organization_id', $organizationId)->where('revisit_flag', false)->count(),
                ];
                break;
            case 'Families':
            default:
                $f = Family::where('organization_id', $organizationId)->with(['volunteer'])->withCount(['members', 'transactions'])->latest();
                if ($from && $to) {
                    $f->whereBetween('created_at', [$from->toDateTimeString(), $to->toDateTimeString()]);
                }
                $data['rows'] = $f->paginate(20)->appends(['type' => $type, 'period' => $period]);
                $data['kpis'] = [
                    'total_families' => Family::where('organization_id', $organizationId)->count(),
                    'zakat_eligible' => Family::where('organization_id', $organizationId)->where('zakat_eligibility', true)->count(),
                    'avg_household_size' => round((float) Family::where('organization_id', $organizationId)->avg('family_members_count'), 1),
                ];

                if ($download === 'pdf') {
                    $rows = $f->get();
                    $pdfData = [
                        'title' => 'Families Report',
                        'subtitle' => ($period ?: 'All Periods'),
                        'columns' => ['Family Name', 'Head', 'Members', 'Zakat Eligible', 'Assigned Volunteer', 'Created At'],
                        'rows' => $rows->map(function ($r) {
                            return [
                                $r->family_name,
                                $r->family_head_name,
                                (int) ($r->members_count ?? $r->members_count ?? $r->members()->count()),
                                $r->zakat_eligibility ? 'Yes' : 'No',
                                optional($r->volunteer)->full_name,
                                optional($r->created_at)->format('Y-m-d'),
                            ];
                        })->toArray(),
                    ];
                    $filename = 'families-report-'.now()->format('Ymd_His').'.pdf';

                    return PDF::loadView('report.pdf.generic', $pdfData)->download($filename);
                }
                break;
        }

        return view('report.report-view')->with($data);
    }

    private function normalizePeriod($period)
    {
        if (! $period) {
            return ['from' => null, 'to' => null];
        }
        $now = now();
        switch ($period) {
            case 'Last 7 Days':
                return ['from' => $now->copy()->subDays(6)->startOfDay(), 'to' => $now->copy()->endOfDay()];
            case 'Last 30 Days':
                return ['from' => $now->copy()->subDays(29)->startOfDay(), 'to' => $now->copy()->endOfDay()];
            case 'This Month':
                return ['from' => $now->copy()->startOfMonth(), 'to' => $now->copy()->endOfMonth()];
            case 'Last Month':
                $lm = $now->copy()->subMonth();

                return ['from' => $lm->startOfMonth(), 'to' => $lm->endOfMonth()];
            case 'This Quarter':
                $q = (int) ceil($now->month / 3);
                $start = now()->firstOfQuarter();
                $end = now()->lastOfQuarter();

                return ['from' => $start, 'to' => $end];
            case 'This Year':
                return ['from' => $now->copy()->startOfYear(), 'to' => $now->copy()->endOfYear()];
            default:
                return ['from' => null, 'to' => null];
        }
    }

    private function toCsv(array $columns, array $rows)
    {
        $fh = fopen('php://temp', 'w+');
        fputcsv($fh, $columns);
        foreach ($rows as $r) { fputcsv($fh, $r); }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);
        return $csv;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
