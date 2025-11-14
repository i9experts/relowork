    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\FamilyController;
    use App\Http\Controllers\ReportController;
    use App\Http\Controllers\SearchController;
    use App\Http\Controllers\SettingController;
    use App\Http\Controllers\SurveysController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\VolunteerController;
    use App\Http\Controllers\AidProgramController;
    use App\Http\Controllers\OrganizationController;
    use App\Http\Controllers\FinancialManagementController;

    // Route::get('/', function () {
    //     return view('welcome');
    // });

    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('cms.dashboard.index');
        }
        return redirect()->route('login');
    })->name('root');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/search', [SearchController::class, 'search'])->name('search');
        Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
        Route::resource('/dashboard', DashboardController::class)->names([
            'index' => 'cms.dashboard.index',
            'create' => 'cms.dashboard.create',
            'store' => 'cms.dashboard.store',
            'show' => 'cms.dashboard.show',
            'edit' => 'cms.dashboard.edit',
            'update' => 'cms.dashboard.update',
            'destroy' => 'cms.dashboard.destroy',
        ]);

        Route::resource('organization-form', OrganizationController::class)->names([
            'index' => 'cms.organization-form.index',
            'create' => 'cms.organization-form.create',
            'store' => 'cms.organization-form.store',
            'show' => 'cms.organization-form.show',
            'edit' => 'cms.organization-form.edit',
            'update' => 'cms.organization-form.update',
            'destroy' => 'cms.organization-form.destroy',
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

        // AJAX helpers for family auto-fill and member CRUD
        Route::get('family/{family}/json', [FamilyController::class, 'json'])->name('cms.family.json');
        Route::post('family/{family}/members', [FamilyController::class, 'addMember'])->name('cms.family.members.add');
        Route::delete('family-members/{member}', [FamilyController::class, 'deleteMember'])->name('cms.family.members.delete');
        Route::post('family/quick-create', [FamilyController::class, 'quickCreate'])->name('cms.family.quick-create');

        Route::resource('aid-program', AidProgramController::class)->names([
            'index' => 'cms.aid-program.index',
            'create' => 'cms.aid-program.create',
            'store' => 'cms.aid-program.store',
            'show' => 'cms.aid-program.show',
            'edit' => 'cms.aid-program.edit',
            'update' => 'cms.aid-program.update',
            'destroy' => 'cms.aid-program.destroy',
        ]);

        // Place export route BEFORE resource to avoid conflict with the 'show' route
        Route::get('financial-management/export', [FinancialManagementController::class, 'export'])
            ->name('cms.financial-management.export');
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

        // Reports: define specific routes before resource to avoid conflicts
        Route::get('report/export', [ReportController::class, 'export'])->name('cms.report.export');
        Route::get('report/download-all', [ReportController::class, 'downloadAll'])->name('cms.report.downloadAll');
        Route::post('reports/generate', [ReportController::class, 'generate'])->name('cms.report.generate');
        // Backward-compat alias in case old cached views still post to report/generate
        Route::post('report/generate', [ReportController::class, 'generate']);
        Route::get('report/view', [ReportController::class, 'view'])->name('cms.report.view');

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
        Route::post('setting/change-password', [SettingController::class, 'changePassword'])->name('cms.setting.change-password');
    });

