<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('dashboard.dashboard');
// });

Route::resource('/', DashboardController::class)->names([
    'index' => 'saas.dashboard.index',
    'create' => 'saas.dashboard.create',
    'store' => 'saas.dashboard.store',
    'show' => 'saas.dashboard.show',
    'edit' => 'saas.dashboard.edit',
    'update' => 'saas.dashboard.update',
    'destroy' => 'saas.dashboard.destroy',
]);