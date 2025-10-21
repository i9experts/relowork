<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AidProgramController;

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

Route::resource('aid-program', AidProgramController::class)->names([
    'index' => 'cms.aid-program.index',
    'create' => 'cms.aid-program.create',
    'store' => 'cms.aid-program.store',
    'show' => 'cms.aid-program.show',
    'edit' => 'cms.aid-program.edit',
    'update' => 'cms.aid-program.update',
    'destroy' => 'cms.aid-program.destroy',
]);
