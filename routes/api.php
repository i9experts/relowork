<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Organization;

Route::get('/organizations/check-domain', function (Request $request) {
    $domain = trim((string) $request->query('domain', ''));

    if ($domain === '') {
        return response()->json(['exists' => false]);
    }

    $exists = Organization::where('domain', $domain)->exists();

    return response()->json(['exists' => $exists]);
});
