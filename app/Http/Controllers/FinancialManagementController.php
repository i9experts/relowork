<?php

namespace App\Http\Controllers;

use App\Models\FinancialManagement;
use Illuminate\Http\Request;

class FinancialManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('financial-management.financial-management-list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial-management.financial_transaction_form');
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
