<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\SurveysController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AidProgramController;
use App\Http\Controllers\FinancialManagementController;

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

Route::resource('financial-management', FinancialManagementController::class)->names([
    'index' => 'cms.financial-management.index',
    'create' => 'cms.financial-management.create',
    'store' => 'cms.financial-management.store',
    'show' => 'cms.financial-management.show',
    'edit' => 'cms.financial-management.edit',
    'update' => 'cms.financial-management.update',
    'destroy' => 'cms.financial-management.destroy',
]);

Route::resource('Survey', SurveysController::class)->names([
    'index' => 'cms.Survey.index',
    'create' => 'cms.Survey.create',
    'store' => 'cms.Survey.store',
    'show' => 'cms.Survey.show',
    'edit' => 'cms.Survey.edit',
    'update' => 'cms.Survey.update',
    'destroy' => 'cms.Survey.destroy',
]);

