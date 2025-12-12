<?php

namespace App\Http\Controllers;

use App\Models\AidProgram;
use App\Models\Family;
use App\Models\FinancialManagement;
use App\Models\Organization;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');

        $data['families'] = Family::where('organization_id', $organizationId)->count();
        $data['aidPrograms'] = AidProgram::where('organization_id', $organizationId)
            ->where('status', 'Active')
            ->count();
        $data['volunteers'] = Volunteer::where('organization_id', $organizationId)->count();
        $data['disbursements'] = FinancialManagement::where('organization_id', $organizationId)
            ->where('transaction_type', 'Disbursement')
            ->sum('amount');
        $data['donationsTotal'] = FinancialManagement::where('organization_id', $organizationId)
            ->where('transaction_type', 'Donation')
            ->sum('amount');
        $data['surveysCount'] = \App\Models\Surveys::where('organization_id', $organizationId)->count();

        $eligible = Family::where('organization_id', $organizationId)->where('zakat_eligibility', true)->count();
        $nonEligible = max(0, ($data['families'] ?? 0) - $eligible);
        $data['familyStatusChart'] = [
            'eligible' => $eligible,
            'nonEligible' => $nonEligible,
        ];

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $incomeByMonth = \DB::table('financial_management')
            ->selectRaw('MONTH(date) as m, SUM(amount) as total')
            ->where('organization_id', $organizationId)
            ->whereYear('date', now()->year)
            ->whereIn('transaction_type', ['Donation', 'Transfer'])
            ->groupBy('m')
            ->pluck('total', 'm');
        $expensesByMonth = \DB::table('financial_management')
            ->selectRaw('MONTH(date) as m, SUM(amount) as total')
            ->where('organization_id', $organizationId)
            ->whereYear('date', now()->year)
            ->whereIn('transaction_type', ['Expense', 'Disbursement'])
            ->groupBy('m')
            ->pluck('total', 'm');
        $income = [];
        $expenses = [];
        for ($m = 1; $m <= 12; $m++) {
            $income[] = (int) ($incomeByMonth[$m] ?? 0);
            $expenses[] = (int) ($expensesByMonth[$m] ?? 0);
        }
        $data['monthlyAid'] = ['months' => $months, 'income' => $income, 'expenses' => $expenses];

        $recentTransactions = FinancialManagement::with(['family', 'aidProgram'])
            ->where('organization_id', $organizationId)
            ->latest('date')
            ->take(5)
            ->get();
        $recentVolunteers = Volunteer::where('organization_id', $organizationId)
            ->latest()
            ->take(5)
            ->get();
        $data['recent'] = ['transactions' => $recentTransactions, 'volunteers' => $recentVolunteers];
        $data['organization'] = $organizationId ? Organization::find($organizationId) : null;

        return view('dashboard.dashboard')->with($data);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
