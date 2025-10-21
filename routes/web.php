<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('dashboard.dashboard');
// });

Route::resource('/', DashboardController::class)->names([
    'index' => 'cms.dashboard.index',
    'create' => 'cms.dashboard.create',
    'store' => 'cms.dashboard.store',
    'show' => 'cms.dashboard.show',
    'edit' => 'cms.dashboard.edit',
    'update' => 'cms.dashboard.update',
    'destroy' => 'cms.dashboard.destroy',
]);

Route::resource('family', FamilyController::class)->names([
    'index' => 'cms.family.index',
    'create' => 'cms.family.create',
    'store' => 'cms.family.store',
    'show' => 'cms.family.show',
    'edit' => 'cms.family.edit',
    'update' => 'cms.family.update',
    'destroy' => 'cms.family.destroy',
]);
