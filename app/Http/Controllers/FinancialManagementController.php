<?php

namespace App\Http\Controllers;

use App\Models\FinancialManagement;
use App\Models\Volunteer;
use App\Models\AidProgram;
use App\Models\Family;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FinancialManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['volunteers'] = Volunteer::latest()->paginate(10);

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
                $from = null; $to = null; // no date filter
                break;
            case 'this_month':
            default:
                $from = $now->copy()->startOfMonth();
                $to = $now->copy()->endOfMonth();
                break;
        }
        $data['range'] = $range;

        // Transactions list (respect range if provided)
        $tx = FinancialManagement::with(['aidProgram','family','recordedBy','approvedBy'])->latest();
        if (isset($from, $to) && $from && $to) {
            $tx->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $data['financialTransactions'] = $tx->paginate(15)->appends(['range' => $range]);

        // KPIs
        $incomeTypes = ['Donation','Transfer'];
        $expenseTypes = ['Expense','Disbursement'];

        $incomeQ = FinancialManagement::whereIn('transaction_type', $incomeTypes);
        $expenseQ = FinancialManagement::whereIn('transaction_type', $expenseTypes);
        if (isset($from, $to) && $from && $to) {
            $incomeQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
            $expenseQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $data['totalIncome'] = (int) $incomeQ->sum('amount');
        $data['totalExpenses'] = (int) $expenseQ->sum('amount');
        $data['netBalance'] = $data['totalIncome'] - $data['totalExpenses'];

        $pendingQ = FinancialManagement::whereNull('approved_by');
        if (isset($from, $to) && $from && $to) {
            $pendingQ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        }
        $data['pendingCount'] = $pendingQ->count();

        return view('financial-management.financial-management-list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['programs'] = AidProgram::orderByDesc('id')->get(['id','program_title']);
        $data['families'] = Family::orderByDesc('id')->get(['id','family_code','head_of_family_name']);
        $data['users'] = User::orderBy('name')->get(['id','name']);
        $data['categories'] = ['Zakat','Sadaqah','Project Fund','Admin Cost'];

        return view('financial-management.financial_transaction_form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required','date'],
            'transaction_type' => ['required','in:Donation,Expense,Disbursement,Transfer'],
            'category' => ['nullable','in:Zakat,Sadaqah,Project Fund,Admin Cost'],
            'payment_mode' => ['nullable','in:Cash,Bank Transfer,Easypaisa,Cheque'],
            'amount' => ['required','integer','min:1'],
            'donor_name' => ['nullable','string','max:255'],
            'aid_program_id' => ['nullable','exists:aid_programs,id'],
            'family_id' => ['nullable','exists:families,id'],
            'payment_proof' => ['nullable','file','max:5120'],
            'recorded_by' => ['nullable','exists:users,id'],
            'approved_by' => ['nullable','exists:users,id'],
            'remarks' => ['nullable','string'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $trxCode = 'TRX-' . now()->format('Y') . '-' . str_pad((string)((FinancialManagement::max('id') ?? 0) + 1), 3, '0', STR_PAD_LEFT);

            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                if ($file->isValid()) {
                    $stored = $file->store('financial_proofs', 'public');
                    $proofPath = $stored; // public disk path
                }
            }

            FinancialManagement::create([
                'transaction_code' => $trxCode,
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialManagement $financialManagement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialManagement $financialManagement)
    {
        //
    }
}
