<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\AidProgram;
use App\Models\FinancialManagement;
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

        // dd($data);
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
