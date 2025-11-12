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
        $data['families'] = Family::count();
        $data['aidPrograms'] = AidProgram::where('status', 'Active')->count();
        $data['volunteers'] = Volunteer::where('status', 'Active')->count();
        $data['disbursements'] = FinancialManagement::where('transaction_type', 'Disbursement')->sum('amount');

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
