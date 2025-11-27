    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\FamilyController;
    use App\Http\Controllers\ReportController;
    use App\Http\Controllers\SearchController;
    use App\Http\Controllers\SettingController;
    use App\Http\Controllers\SurveysController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
    use App\Http\Controllers\VolunteerController;
    use App\Http\Controllers\VolunteerSettingsController;
    use App\Http\Controllers\AidProgramController;
    use App\Http\Controllers\OrganizationController;
    use App\Http\Controllers\FinancialManagementController;
    use App\Models\Organization;

    // Route::get('/', function () {
    //     return view('welcome');
    // });

    Route::get('/', function () {
        // Agar koi organization hi nahi bani hui to pehle organization setup form dikhayein
        if (Organization::count() === 0) {
            return redirect()->route('organization.register');
        }

        // Agar user logged in hai to unke role ke mutabiq redirect karein
        if (auth()->check()) {
            $u = auth()->user();
            if ($u && ($u->is_admin || (method_exists($u, 'isSystemAdmin') && $u->isSystemAdmin()))) {
                return redirect()->route('cms.admin.dashboard');
            }
            return redirect()->route('cms.dashboard.index');
        }

        // Warna login page dikhayein
        return redirect()->route('login');
    })->name('root');

    // Public organization registration (pehla step) - bina login ke accessible
    Route::get('/organization/register', [OrganizationController::class, 'index'])->name('organization.register');
    Route::post('/organization/register', [OrganizationController::class, 'store'])->name('organization.register.store');

    // Organization email OTP verification routes
    Route::get('/organization/{organization}/otp', [\App\Http\Controllers\OrganizationOtpController::class, 'show'])->name('organization.otp.show');
    Route::post('/organization/{organization}/otp', [\App\Http\Controllers\OrganizationOtpController::class, 'verify'])->name('organization.otp.verify');
    Route::post('/organization/{organization}/otp/resend', [\App\Http\Controllers\OrganizationOtpController::class, 'resend'])->name('organization.otp.resend');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [\App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.update');
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

        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('cms.admin.dashboard')
            ->middleware('permission:super_admin');

        Route::get('/admin/organizations', [AdminDashboardController::class, 'organizations'])
            ->name('cms.admin.organizations')
            ->middleware('permission:super_admin');

        Route::get('/admin/organizations/{organization}/invoice', [AdminDashboardController::class, 'invoice'])
            ->name('cms.admin.organizations.invoice')
            ->middleware('permission:super_admin');

        Route::get('/admin/users', [AdminDashboardController::class, 'users'])
            ->name('cms.admin.users')
            ->middleware('permission:super_admin');

        Route::resource('organization-form', OrganizationController::class)->names([
            'index' => 'cms.organization-form.index',
            'create' => 'cms.organization-form.create',
            'store' => 'cms.organization-form.store',
            'show' => 'cms.organization-form.show',
            'edit' => 'cms.organization-form.edit',
            'update' => 'cms.organization-form.update',
            'destroy' => 'cms.organization-form.destroy',
        ]);

        Route::middleware('permission:families')->group(function () {
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
        });

        Route::middleware('permission:aid_programs')->group(function () {
        Route::resource('aid-program', AidProgramController::class)->names([
            'index' => 'cms.aid-program.index',
            'create' => 'cms.aid-program.create',
            'store' => 'cms.aid-program.store',
            'show' => 'cms.aid-program.show',
            'edit' => 'cms.aid-program.edit',
            'update' => 'cms.aid-program.update',
            'destroy' => 'cms.aid-program.destroy',
        ]);
        });

        // Place export route BEFORE resource to avoid conflict with the 'show' route
        Route::middleware('permission:financial')->group(function () {
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
        });

        Route::middleware('permission:surveys')->group(function () {
        Route::resource('survey', SurveysController::class)->names([
            'index' => 'cms.survey.index',
            'create' => 'cms.survey.create',
            'store' => 'cms.survey.store',
            'show' => 'cms.survey.show',
            'edit' => 'cms.survey.edit',
            'update' => 'cms.survey.update',
            'destroy' => 'cms.survey.destroy',
        ]);
        });

        // Reports: define specific routes before resource to avoid conflicts
        Route::middleware('permission:reports')->group(function () {
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
        });

        Route::get('volunteer-settings', [VolunteerSettingsController::class, 'index'])->name('cms.volunteer-settings.index');
        Route::post('volunteer-settings/education-level', [VolunteerSettingsController::class, 'storeEducationLevel'])->name('cms.volunteer-settings.education-level.store');
        Route::post('volunteer-settings/availability', [VolunteerSettingsController::class, 'storeAvailability'])->name('cms.volunteer-settings.availability.store');
        Route::post('volunteer-settings/project-type', [VolunteerSettingsController::class, 'storeProjectType'])->name('cms.volunteer-settings.project-type.store');
        Route::post('volunteer-settings/volunteer-role', [VolunteerSettingsController::class, 'storeVolunteerRole'])->name('cms.volunteer-settings.volunteer-role.store');

        Route::middleware('permission:volunteers')->group(function () {
            Route::resource('volunteer', VolunteerController::class)->names([
                'index' => 'cms.volunteer.index',
                'create' => 'cms.volunteer.create',
                'store' => 'cms.volunteer.store',
                'show' => 'cms.volunteer.show',
                'edit' => 'cms.volunteer.edit',
                'update' => 'cms.volunteer.update',
                'destroy' => 'cms.volunteer.destroy',
            ]);
        });

        Route::middleware('permission:settings')->group(function () {
        Route::resource('setting', SettingController::class)->names([
            'index' => 'cms.setting.index',
            'create' => 'cms.setting.create',
            'store' => 'cms.setting.store',
            'show' => 'cms.setting.show',
            'edit' => 'cms.setting.edit',
            'update' => 'cms.setting.update',
            'destroy' => 'cms.setting.destroy',
        ]);
        });

        // Profile page: accessible to all authenticated users
        Route::get('profile', [SettingController::class, 'profile'])->name('cms.profile.index');
        Route::post('profile/update', [SettingController::class, 'updateProfile'])->name('cms.setting.update-profile');
        Route::post('profile/change-password', [SettingController::class, 'changePassword'])->name('cms.setting.change-password');
    });
