<?php

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:30,1')->get('/organizations/check-domain', function (Request $request) {
    $domain = trim((string) $request->query('domain', ''));

    if ($domain === '') {
        return response()->json(['exists' => false]);
    }

    $exists = Organization::where('domain', $domain)->exists();

    return response()->json(['exists' => $exists]);
});
