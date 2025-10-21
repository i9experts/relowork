<?php

namespace App\Http\Controllers;

use App\Models\AidProgram;
use Illuminate\Http\Request;

class AidProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('aid-program.aid-program-list');
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
    public function show(AidProgram $aidProgram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AidProgram $aidProgram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AidProgram $aidProgram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AidProgram $aidProgram)
    {
        //
    }
}
