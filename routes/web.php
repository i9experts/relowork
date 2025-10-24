<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SurveysController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VolunteerController;
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

Route::resource('survey', SurveysController::class)->names([
    'index' => 'cms.survey.index',
    'create' => 'cms.survey.create',
    'store' => 'cms.survey.store',
    'show' => 'cms.survey.show',
    'edit' => 'cms.survey.edit',
    'update' => 'cms.survey.update',
    'destroy' => 'cms.survey.destroy',
]);

Route::resource('report', ReportController::class)->names([
    'index' => 'cms.report.index',
    'create' => 'cms.report.create',
    'store' => 'cms.report.store',
    'show' => 'cms.report.show',
    'edit' => 'cms.report.edit',
    'update' => 'cms.report.update',
    'destroy' => 'cms.report.destroy',
]);

Route::resource('volunteer', VolunteerController::class)->names([
    'index' => 'cms.volunteer.index',
    'create' => 'cms.volunteer.create',
    'store' => 'cms.volunteer.store',
    'show' => 'cms.volunteer.show',
    'edit' => 'cms.volunteer.edit',
    'update' => 'cms.volunteer.update',
    'destroy' => 'cms.volunteer.destroy',
]);

Route::resource('setting', SettingController::class)->names([
    'index' => 'cms.setting.index',
    'create' => 'cms.setting.create',
    'store' => 'cms.setting.store',
    'show' => 'cms.setting.show',
    'edit' => 'cms.setting.edit',
    'update' => 'cms.setting.update',
    'destroy' => 'cms.setting.destroy',
]);


