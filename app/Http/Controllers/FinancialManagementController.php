<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMailer;
use App\Models\AidProgram;
use App\Models\Family;
use App\Models\FinancialManagement;
use App\Models\User;
use App\Models\Volunteer;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Mail\SystemNotificationMailer;

class FinancialManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:financial')->only(['index','show']);
        $this->middleware('permission:financial_create')->only(['create','store']);
        $this->middleware('permission:financial_edit')->only(['edit','update']);
        $this->middleware('permission:financial_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');

        $data['volunteers'] = Volunteer::where('organization_id', $organizationId)
            ->latest()->paginate(10);

        // Range filter (optional; defaults to this_month)
        $range = request('range', 'this_month');
        $now = now();
        switch ($range) {
            case 'last_month':
                $from = $now->copy()->subMonthNoOverflow()->startOfMonth();
                $to = $now->copy()->subMonthNoOverflow()->endOfMonth();
                break;
            case 'this_quarter':
                $from = $now->copy()->firstOfQuarter()->startOfDay();
                $to = $now->copy()->lastOfQuarter()->endOfDay();
                break;
            case 'this_year':
                $from = $now->copy()->startOfYear();
                $to = $now->copy()->endOfYear();
                break;
            case 'all_time':
                $from = null;
                $to = null; // no date filter
                break;
            case 'this_month':
            default:
                $from = $now->copy()->startOfMonth();
                $to = $now->copy()->endOfMonth();
                break;
        }
        $data['range'] = $range;

        // Build dynamic titles for chart/header based on selected range
        $labelMap = [
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_quarter' => 'This Quarter',
            'this_year' => 'This Year',
            'all_time' => 'All Time',
        ];
        $periodLabel = $labelMap[$range] ?? 'This Month';

        // More detailed period for subtitle
        if ($range === 'this_month') {
            $periodDetail = $now->format('F Y');
        } elseif ($range === 'last_month') {
            $periodDetail = $from->format('F Y');
        } elseif ($range === 'this_quarter') {
            $periodDetail = 'Q'.$from->quarter.' '.$from->format('Y');
        } elseif ($range === 'this_year') {
            $periodDetail = $from->format('Y');
        } elseif ($range === 'all_time') {
            $periodDetail = 'All Time';
        } else {
            $periodDetail = $periodLabel;
        }

        $data['chartTitle'] = 'Financial Overview — '.$periodLabel;
        $data['chartSubtitle'] = 'Income vs Expenses for '.$periodDetail;

        // Build dynamic chart data (labels + income/expense series)
        $labels = [];
        $incomeSeries = [];
        $expenseSeries = [];

        $incomeTypes = ['Donation', 'Transfer'];
        $expenseTypes = ['Expense', 'Disbursement'];

        if (! isset($from, $to) || ! $from || ! $to) {
            $start = $now->copy()->subMonthsNoOverflow(11)->startOfMonth();
            $end = $now->copy()->endOfMonth();
            $period = CarbonPeriod::create($start, '1 month', $end);
            foreach ($period as $m) {
                $mStart = $m->copy()->startOfMonth();
                $mEnd = $m->copy()->endOfMonth();
                $labels[] = $m->format('M Y');
                $incomeSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)
                    ->whereIn('transaction_type', $incomeTypes)
                    ->whereBetween('date', [$mStart->toDateString(), $mEnd->toDateString()])
                    ->sum('amount');
                $expenseSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)
                    ->whereIn('transaction_type', $expenseTypes)
                    ->whereBetween('date', [$mStart->toDateString(), $mEnd->toDateString()])
                    ->sum('amount');
            }
        } else {
            $isDaily = in_array($range, ['this_month', 'last_month'], true);
            if ($isDaily) {
                $period = CarbonPeriod::create($from->copy()->startOfDay(), '1 day', $to->copy()->endOfDay());
                foreach ($period as $d) {
                    $dStart = $d->copy()->startOfDay();
                    $dEnd = $d->copy()->endOfDay();
                    $labels[] = $d->format('d M');
                    $incomeSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)
                        ->whereIn('transaction_type', $incomeTypes)
                        ->whereBetween('date', [$dStart->toDateString(), $dEnd->toDateString()])
                        ->sum('amount');
                    $expenseSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)
                        ->whereIn('transaction_type', $expenseTypes)
                        ->whereBetween('date', [$dStart->toDateString(), $dEnd->toDateString()])
                        ->sum('amount');
                }
            } else {
                $mStart = $from->copy()->startOfMonth();
                $mEnd = $to->copy()->endOfMonth();
                $period = CarbonPeriod::create($mStart, '1 month', $mEnd);
                foreach ($period as $m) {
                    $mmStart = $m->copy()->startOfMonth();
                    $mmEnd = $m->copy()->endOfMonth();
                    $labels[] = $m->format('M Y');
                    $incomeSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)
                        ->whereIn('transaction_type', $incomeTypes)
                        ->whereBetween('date', [$mmStart->toDateString(), $mmEnd->toDateString()])
                        ->sum('amount');
                    $expenseSeries[] = (int) FinancialManagement::whereIn('transaction_type', $expenseTypes)
                        ->whereBetween('date', [$mmStart->toDateString(), $mmEnd->toDateString()])
                        ->sum('amount');
                }
            }
        }

        $data['chartLabels'] = $labels;
        $data['chartIncome'] = $incomeSeries;
        $data['chartExpenses'] = $expenseSeries;

        // Transactions list (respect range if provided)
        $tx = FinancialManagement::with(['aidProgram', 'family', 'recordedBy', 'approvedBy'])
            ->where('organization_id', $organizationId)
            ->latest();
        if (isset($from, $to) && $from && $to) {
            $tx->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $data['financialTransactions'] = $tx->paginate(15)->appends(['range' => $range]);

        // KPIs
        $incomeTypes = ['Donation', 'Transfer'];
        $expenseTypes = ['Expense', 'Disbursement'];

        $incomeQ = FinancialManagement::where('organization_id', $organizationId)
            ->whereIn('transaction_type', $incomeTypes);
        $expenseQ = FinancialManagement::where('organization_id', $organizationId)
            ->whereIn('transaction_type', $expenseTypes);
        if (isset($from, $to) && $from && $to) {
            $incomeQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
            $expenseQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $data['totalIncome'] = (int) $incomeQ->sum('amount');
        $data['totalExpenses'] = (int) $expenseQ->sum('amount');
        $data['netBalance'] = $data['totalIncome'] - $data['totalExpenses'];

        $pendingQ = FinancialManagement::where('organization_id', $organizationId)
            ->whereNull('approved_by');
        if (isset($from, $to) && $from && $to) {
            $pendingQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $data['pendingCount'] = $pendingQ->count();

        // dd($data);

        return view('financial-management.financial-management-list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');
        $data['programs'] = AidProgram::where('organization_id', $organizationId)->orderByDesc('id')->get(['id', 'program_title']);
        $data['families'] = Family::where('organization_id', $organizationId)->orderByDesc('id')->get(['id', 'family_code', 'head_of_family_name']);
        $data['users'] = User::where('organization_id', $organizationId)->orderBy('name')->get(['id', 'name']);
        $data['categories'] = ['Zakat', 'Sadaqah', 'Project Fund', 'Admin Cost'];

        return view('financial-management.financial_transaction_form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organizationId = optional($request->user())->organization_id ?? session('organization_id');
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'transaction_type' => ['required', 'in:Donation,Expense,Disbursement,Transfer'],
            'category' => ['nullable', 'in:Zakat,Sadaqah,Project Fund,Admin Cost'],
            'payment_mode' => ['nullable', 'in:Cash,Bank Transfer,Easypaisa,Cheque'],
            'amount' => ['required', 'integer', 'min:1'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'aid_program_id' => ['required', Rule::exists('aid_programs', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'family_id' => ['required', Rule::exists('families', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'payment_proof' => ['nullable', 'file', 'max:5120'],
            'recorded_by' => ['required', Rule::exists('users', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'approved_by' => ['nullable', Rule::exists('users', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'remarks' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $validated, $organizationId) {
            $trxCode = 'TRX-'.now()->format('Y').'-'.str_pad((string) ((FinancialManagement::max('id') ?? 0) + 1), 3, '0', STR_PAD_LEFT);

            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                if ($file->isValid()) {
                    $stored = $file->store('financial_proofs', 'public');
                    $proofPath = $stored; // public disk path
                }
            }

            $tx = FinancialManagement::create([
                'transaction_code' => $trxCode,
                'organization_id' => $organizationId,
                'date' => $validated['date'],
                'transaction_type' => $validated['transaction_type'],
                'category' => $validated['category'] ?? null,
                'payment_mode' => $validated['payment_mode'] ?? null,
                'amount' => $validated['amount'],
                'donor_name' => $validated['donor_name'] ?? null,
                'aid_program_id' => $validated['aid_program_id'] ?? null,
                'family_id' => $validated['family_id'] ?? null,
                'payment_proof_path' => $proofPath,
                'recorded_by' => $validated['recorded_by'] ?? auth()->id(),
                'approved_by' => $validated['approved_by'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Notify admins based on settings
            try {
                if (in_array($validated['transaction_type'], ['Expense', 'Disbursement'], true) && empty($validated['approved_by'])) {
                    NotificationMailer::sendAidApprovalAlert($organizationId, ['actionUrl' => route('cms.financial-management.index')]);
                }
                // Generic activity notification
                NotificationMailer::sendEmailNotification($organizationId, ['actionUrl' => route('cms.financial-management.index')]);
                // Optional push-email for Donation events
                if ($validated['transaction_type'] === 'Donation') {
                    NotificationMailer::sendPushEmail($organizationId, ['actionUrl' => route('cms.financial-management.index')]);
                }
            } catch (\Throwable $e) { /* silent */
                try { SystemNotificationMailer::sendSystemErrorAlert(['message' => 'Org notification send failed', 'context' => $e->getMessage()]); } catch (\Throwable $e2) {}
            }

            // System-level high value alert
            try {
                $threshold = (int) (env('HIGH_VALUE_TX_THRESHOLD', 100000));
                if ((int) $tx->amount >= $threshold) {
                    SystemNotificationMailer::sendHighValueTransactionAlert([
                        'org_name' => optional(\App\Models\Organization::find($organizationId))->organization_name,
                        'amount' => (float) $tx->amount,
                        'type' => $tx->transaction_type,
                        'transaction_code' => $tx->transaction_code,
                        'recorded_by_name' => optional($tx->recordedBy)->name,
                        'actionUrl' => route('cms.financial-management.index'),
                        'reason' => 'Threshold exceeded',
                    ]);
                }
            } catch (\Throwable $e) {}
        });

        return redirect()->route('cms.financial-management.index')->with('success', 'Transaction saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialManagement $financialManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialManagement $financialManagement)
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');
        $data['programs'] = AidProgram::where('organization_id', $organizationId)->orderByDesc('id')->get(['id', 'program_title']);
        $data['families'] = Family::where('organization_id', $organizationId)->orderByDesc('id')->get(['id', 'family_code', 'head_of_family_name']);
        $data['users'] = User::where('organization_id', $organizationId)->orderBy('name')->get(['id', 'name']);
        $data['categories'] = ['Zakat', 'Sadaqah', 'Project Fund', 'Admin Cost'];
        $data['financialManagement'] = $financialManagement;

        return view('financial-management.financial-transaction-edit-form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialManagement $financialManagement)
    {
        $organizationId = optional($request->user())->organization_id ?? session('organization_id');
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'transaction_type' => ['required', 'in:Donation,Expense,Disbursement,Transfer'],
            'category' => ['nullable', 'in:Zakat,Sadaqah,Project Fund,Admin Cost'],
            'payment_mode' => ['nullable', 'in:Cash,Bank Transfer,Easypaisa,Cheque'],
            'amount' => ['required', 'integer', 'min:1'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'aid_program_id' => ['required', Rule::exists('aid_programs', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'family_id' => ['required', Rule::exists('families', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'payment_proof' => ['nullable', 'file', 'max:5120'],
            'recorded_by' => ['required', Rule::exists('users', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'approved_by' => ['nullable', Rule::exists('users', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'remarks' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $validated, $financialManagement) {
            // Handle file replacement if provided
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                if ($file->isValid()) {
                    // delete old proof if any
                    if ($financialManagement->payment_proof_path && Storage::disk('public')->exists($financialManagement->payment_proof_path)) {
                        Storage::disk('public')->delete($financialManagement->payment_proof_path);
                    }
                    $stored = $file->store('financial_proofs', 'public');
                    $financialManagement->payment_proof_path = $stored;
                }
            }

            $financialManagement->date = $validated['date'];
            $financialManagement->transaction_type = $validated['transaction_type'];
            $financialManagement->category = $validated['category'] ?? null;
            $financialManagement->payment_mode = $validated['payment_mode'] ?? null;
            $financialManagement->amount = $validated['amount'];
            $financialManagement->donor_name = $validated['donor_name'] ?? null;
            $financialManagement->aid_program_id = $validated['aid_program_id'] ?? null;
            $financialManagement->family_id = $validated['family_id'] ?? null;
            $financialManagement->recorded_by = $validated['recorded_by'] ?? $financialManagement->recorded_by;
            $financialManagement->approved_by = $validated['approved_by'] ?? $financialManagement->approved_by;
            $financialManagement->remarks = $validated['remarks'] ?? null;

            $financialManagement->save();
        });

        return redirect()->route('cms.financial-management.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialManagement $financialManagement)
    {
        DB::transaction(function () use ($financialManagement) {
            if ($financialManagement->payment_proof_path && Storage::disk('public')->exists($financialManagement->payment_proof_path)) {
                Storage::disk('public')->delete($financialManagement->payment_proof_path);
            }
            $financialManagement->delete();
        });

        return redirect()->route('cms.financial-management.index')->with('success', 'Transaction deleted successfully.');
    }

    public function export(Request $request)
    {
        $organizationId = optional($request->user())->organization_id ?? session('organization_id');
        // 1) Resolve range (same as index)
        $range = $request->query('range', 'this_month');
        $now = now();
        switch ($range) {
            case 'last_month':
                $from = $now->copy()->subMonthNoOverflow()->startOfMonth();
                $to = $now->copy()->subMonthNoOverflow()->endOfMonth();
                break;
            case 'this_quarter':
                $from = $now->copy()->firstOfQuarter()->startOfDay();
                $to = $now->copy()->lastOfQuarter()->endOfDay();
                break;
            case 'this_year':
                $from = $now->copy()->startOfYear();
                $to = $now->copy()->endOfYear();
                break;
            case 'all_time':
                $from = null;
                $to = null; // no filter
                break;
            case 'this_month':
            default:
                $from = $now->copy()->startOfMonth();
                $to = $now->copy()->endOfMonth();
                break;
        }

        $labelMap = [
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_quarter' => 'This Quarter',
            'this_year' => 'This Year',
            'all_time' => 'All Time',
        ];
        $periodLabel = $labelMap[$range] ?? 'This Month';
        if ($range === 'this_month') {
            $periodDetail = $now->format('F Y');
        } elseif ($range === 'last_month') {
            $periodDetail = $from->format('F Y');
        } elseif ($range === 'this_quarter') {
            $periodDetail = 'Q'.$from->quarter.' '.$from->format('Y');
        } elseif ($range === 'this_year') {
            $periodDetail = $from->format('Y');
        } elseif ($range === 'all_time') {
            $periodDetail = 'All Time';
        } else {
            $periodDetail = $periodLabel;
        }

        // 2) Build chart labels + series (same logic as index)
        $labels = [];
        $incomeSeries = [];
        $expenseSeries = [];
        $incomeTypes = ['Donation', 'Transfer'];
        $expenseTypes = ['Expense', 'Disbursement'];

        if (! isset($from, $to) || ! $from || ! $to) {
            $start = $now->copy()->subMonthsNoOverflow(11)->startOfMonth();
            $end = $now->copy()->endOfMonth();
            $period = \Carbon\CarbonPeriod::create($start, '1 month', $end);
            foreach ($period as $m) {
                $mStart = $m->copy()->startOfMonth();
                $mEnd = $m->copy()->endOfMonth();
                $labels[] = $m->format('M Y');
                $incomeSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $incomeTypes)
                    ->whereBetween('date', [$mStart->toDateString(), $mEnd->toDateString()])
                    ->sum('amount');
                $expenseSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $expenseTypes)
                    ->whereBetween('date', [$mStart->toDateString(), $mEnd->toDateString()])
                    ->sum('amount');
            }
        } else {
            $isDaily = in_array($range, ['this_month', 'last_month'], true);
            if ($isDaily) {
                $period = \Carbon\CarbonPeriod::create($from->copy()->startOfDay(), '1 day', $to->copy()->endOfDay());
                foreach ($period as $d) {
                    $dStart = $d->copy()->startOfDay();
                    $dEnd = $d->copy()->endOfDay();
                    $labels[] = $d->format('d M');
                    $incomeSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $incomeTypes)
                        ->whereBetween('date', [$dStart->toDateString(), $dEnd->toDateString()])
                        ->sum('amount');
                    $expenseSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $expenseTypes)
                        ->whereBetween('date', [$dStart->toDateString(), $dEnd->toDateString()])
                        ->sum('amount');
                }
            } else {
                $mStart = $from->copy()->startOfMonth();
                $mEnd = $to->copy()->endOfMonth();
                $period = \Carbon\CarbonPeriod::create($mStart, '1 month', $mEnd);
                foreach ($period as $m) {
                    $mmStart = $m->copy()->startOfMonth();
                    $mmEnd = $m->copy()->endOfMonth();
                    $labels[] = $m->format('M Y');
                    $incomeSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $incomeTypes)
                        ->whereBetween('date', [$mmStart->toDateString(), $mmEnd->toDateString()])
                        ->sum('amount');
                    $expenseSeries[] = (int) FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $expenseTypes)
                        ->whereBetween('date', [$mmStart->toDateString(), $mmEnd->toDateString()])
                        ->sum('amount');
                }
            }
        }

        // 3) KPIs
        $incomeQ = FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $incomeTypes);
        $expenseQ = FinancialManagement::where('organization_id', $organizationId)->whereIn('transaction_type', $expenseTypes);
        if (isset($from, $to) && $from && $to) {
            $incomeQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
            $expenseQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $totalIncome = (int) $incomeQ->sum('amount');
        $totalExpenses = (int) $expenseQ->sum('amount');
        $netBalance = $totalIncome - $totalExpenses;
        $pendingQ = FinancialManagement::where('organization_id', $organizationId)->whereNull('approved_by');
        if (isset($from, $to) && $from && $to) {
            $pendingQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $pendingCount = (int) $pendingQ->count();

        // 4) Transactions for the section
        $tx = FinancialManagement::with(['aidProgram', 'family', 'recordedBy', 'approvedBy'])->where('organization_id', $organizationId)->orderByDesc('date');
        if (isset($from, $to) && $from && $to) {
            $tx->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $rows = $tx->get();

        // 5) Stream CSV with sections
        $filename = 'financial-report-'.$range.'-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($periodLabel, $periodDetail, $labels, $incomeSeries, $expenseSeries, $totalIncome, $totalExpenses, $netBalance, $pendingCount, $rows) {
            $out = fopen('php://output', 'w');

            // Header section
            fputcsv($out, ['Financial Overview — '.$periodLabel]);
            fputcsv($out, ['Income vs Expenses for '.$periodDetail]);
            fputcsv($out, ['Generated At', now()->toDateTimeString()]);
            fputcsv($out, []);

            // KPIs
            fputcsv($out, ['Top Statistics']);
            fputcsv($out, ['Total Income', $totalIncome]);
            fputcsv($out, ['Total Expenses', $totalExpenses]);
            fputcsv($out, ['Net Balance', $netBalance]);
            fputcsv($out, ['Pending Transactions', $pendingCount]);
            fputcsv($out, []);

            // Chart Data
            fputcsv($out, ['Chart Data']);
            fputcsv($out, ['Label', 'Income', 'Expenses']);
            $count = max(count($labels), count($incomeSeries), count($expenseSeries));
            for ($i = 0; $i < $count; $i++) {
                $lbl = $labels[$i] ?? '';
                $inc = (int) ($incomeSeries[$i] ?? 0);
                $exp = (int) ($expenseSeries[$i] ?? 0);
                fputcsv($out, [$lbl, $inc, $exp]);
            }
            fputcsv($out, []);

            // Transactions
            fputcsv($out, ['Transactions']);
            fputcsv($out, [
                'S.no',
                'Transaction Code',
                'Date',
                'Donation Type',
                'Payment Mode',
                'Donor Name',
                'Amount',
                'Aid Program Name',
                'Family Name',
                'Recorded By',
                'Approved By',
            ]);
            $sn = 1;
            foreach ($rows as $r) {
                fputcsv($out, [
                    $sn++,
                    $r->transaction_code,
                    $r->date,
                    $r->transaction_type,
                    $r->payment_mode,
                    $r->donor_name,
                    (int) $r->amount,
                    optional($r->aidProgram)->program_title,
                    optional($r->family)->head_of_family_name,
                    optional($r->recordedBy)->name,
                    optional($r->approvedBy)->name,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
