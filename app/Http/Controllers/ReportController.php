<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Family;
use App\Models\Volunteer;
use App\Models\AidProgram;
use App\Models\Surveys;
use App\Models\FinancialManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
            'type' => ['required','string','max:100'],
            'period' => ['required','string','max:100'],
        ],
        [
            'type.required' => 'Please select a report type.',
            'period.required' => 'Please select a report period.',
        ]);

        $data['reports'] = Report::query()->latest()->get();
        $data['should_load_report_list1'] = true;
        $data['filters'] = [
            'type' => $validated['type'] ?? null,
            'period' => $validated['period'] ?? null,
        ];

        return view('report.report')->with($data);
    }

    public function export(Request $request)
    {
        $type = $request->query('type');
        $period = $request->query('period');

        $filename = 'report'.($type ? '-'.preg_replace('/\s+/', '-', strtolower($type)) : '').($period ? '-'.preg_replace('/\s+/', '-', strtolower($period)) : '').'-'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function() use ($type, $period) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Report Type', 'Period', 'Generated At']);
            fputcsv($handle, [$type ?: 'All', $period ?: 'All', now()->toDateTimeString()]);
            // TODO: Write real rows based on $type/$period filters
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadAll()
    {
        $zip = new \ZipArchive();
        $tmpPath = storage_path('app/tmp');
        if (!is_dir($tmpPath)) { @mkdir($tmpPath, 0777, true); }
        $zipPath = $tmpPath.'/all-reports-'.now()->format('Ymd_His').'.zip';

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create ZIP');
        }

        // Minimal placeholder: include a summary CSV
        $summary = "Type,Period,Generated At\nAll,All,".now()->toDateTimeString()."\n";
        $zip->addFromString('summary.csv', $summary);

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function view(Request $request)
    {
        $type = $request->query('type');
        $period = $request->query('period');

        $range = $this->normalizePeriod($period);
        $from = $range['from'] ?? null;
        $to = $range['to'] ?? null;

        $data = [
            'filters' => [ 'type' => $type, 'period' => $period ],
            'type' => $type,
            'period' => $period,
        ];

        switch ($type) {
            case 'Financial':
                $q = FinancialManagement::with(['aidProgram','family','recordedBy','approvedBy'])->latest();
                if ($from && $to) { $q->whereBetween('date', [$from->toDateString(), $to->toDateString()]); }
                $data['rows'] = $q->paginate(20)->appends(['type'=>$type,'period'=>$period]);
                $incomeTypes = ['Donation','Transfer'];
                $expenseTypes = ['Expense','Disbursement'];
                $incomeQ = FinancialManagement::whereIn('transaction_type',$incomeTypes);
                $expenseQ = FinancialManagement::whereIn('transaction_type',$expenseTypes);
                if ($from && $to) { $incomeQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]); $expenseQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]); }
                $totalIncome = (int)$incomeQ->sum('amount');
                $totalExpenses = (int)$expenseQ->sum('amount');
                $data['kpis'] = [
                    'total_income' => $totalIncome,
                    'total_expenses' => $totalExpenses,
                    'net_balance' => $totalIncome - $totalExpenses,
                    'pending_transactions' => FinancialManagement::whereNull('approved_by')->count(),
                ];
                break;
            case 'Volunteers':
                $v = Volunteer::latest();
                $data['rows'] = $v->paginate(20)->appends(['type'=>$type,'period'=>$period]);
                $data['kpis'] = [
                    'total_volunteers' => Volunteer::count(),
                    'active_this_month' => Volunteer::where('status','Active')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->count(),
                ];
                break;
            case 'Aid Programs':
                $p = AidProgram::withCount(['families','volunteers','attachments'])
                    ->withSum(['transactions as disbursed_amount' => function ($q) { $q->where('transaction_type','Disbursement'); }], 'amount')
                    ->latest();
                $data['rows'] = $p->paginate(20)->appends(['type'=>$type,'period'=>$period]);
                $data['kpis'] = [
                    'active_programs' => AidProgram::where('status','Active')->count(),
                    'total_budget_active' => (int) AidProgram::where('status','Active')->sum('total_budget'),
                    'beneficiaries_total' => (int) AidProgram::sum('target_beneficiaries'),
                ];
                break;
            case 'Survey':
                $q = Surveys::with(['enumerator','family'])->withCount(['members','uploads'])->latest();
                if ($from && $to) { $q->whereBetween('created_at', [$from->toDateTimeString(), $to->toDateTimeString()]); }
                $data['rows'] = $q->paginate(20)->appends(['type'=>$type,'period'=>$period]);
                $data['kpis'] = [
                    'total_surveys' => Surveys::count(),
                    'active_surveys' => Surveys::where('revisit_flag', true)->count(),
                    'completed_surveys' => Surveys::where('revisit_flag', false)->count(),
                ];
                break;
            case 'Families':
            default:
                $f = Family::with(['volunteer'])->withCount(['members','transactions'])->latest();
                if ($from && $to) { $f->whereBetween('created_at', [$from->toDateTimeString(), $to->toDateTimeString()]); }
                $data['rows'] = $f->paginate(20)->appends(['type'=>$type,'period'=>$period]);
                $data['kpis'] = [
                    'total_families' => Family::count(),
                    'zakat_eligible' => Family::where('zakat_eligibility', true)->count(),
                    'avg_household_size' => round((float) Family::avg('family_members_count'), 1),
                ];
                break;
        }

        return view('report.report-view')->with($data);
    }

    private function normalizePeriod($period)
    {
        if (!$period) return ['from'=>null,'to'=>null];
        $now = now();
        switch ($period) {
            case 'This Month':
                return ['from'=>$now->copy()->startOfMonth(), 'to'=>$now->copy()->endOfMonth()];
            case 'Last Month':
                $lm = $now->copy()->subMonth();
                return ['from'=>$lm->startOfMonth(), 'to'=>$lm->endOfMonth()];
            case 'This Quarter':
                $q = (int)ceil($now->month/3);
                $start = now()->firstOfQuarter();
                $end = now()->lastOfQuarter();
                return ['from'=>$start, 'to'=>$end];
            case 'This Year':
                return ['from'=>$now->copy()->startOfYear(), 'to'=>$now->copy()->endOfYear()];
            default:
                return ['from'=>null,'to'=>null];
        }
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
