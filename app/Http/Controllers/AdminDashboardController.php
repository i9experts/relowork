<?php

namespace App\Http\Controllers;

use App\Models\AidProgram;
use App\Models\Family;
use App\Models\FinancialManagement;
use App\Models\Organization;
use App\Models\Surveys;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->isSystemAdmin()) {
            return redirect()->route('cms.dashboard.index')->with('error', 'Unauthorized: Super Admin access required.');
        }

        $data['organizations'] = Organization::count();
        $data['users'] = User::whereNotNull('organization_id')->count();
        $data['families'] = Family::whereNotNull('organization_id')->count();
        $data['volunteers'] = Volunteer::whereNotNull('organization_id')->count();
        $data['aidPrograms'] = AidProgram::whereNotNull('organization_id')->count();
        $data['donationsTotal'] = (int) FinancialManagement::where('transaction_type', 'Donation')->sum('amount');
        $data['expensesTotal'] = (int) FinancialManagement::whereIn('transaction_type', ['Expense', 'Disbursement'])->sum('amount');
        $data['netBalance'] = $data['donationsTotal'] - $data['expensesTotal'];
        $data['activeOrganizations'] = Organization::where('active_status', true)->count();
        $data['inactiveOrganizations'] = Organization::where('active_status', false)->count();

        $data['recentOrganizations'] = Organization::orderByDesc('id')->take(10)->get(['id', 'organization_name', 'email', 'active_status', 'created_at']);

        // Monthly org registrations (last 12 months)
        $orgReg = Organization::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as c')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();
        $months = collect(range(0, 11))->map(function ($i) {
            return now()->subMonths(11 - $i)->format('Y-m');
        });
        $orgRegMap = collect($orgReg)->keyBy('ym');
        $data['months'] = $months->map(function ($m) {
            return \Illuminate\Support\Carbon::parse($m.'-01')->format('M Y');
        })->toArray();
        $data['orgRegistrations'] = $months->map(function ($m) use ($orgRegMap) {
            return (int) (optional($orgRegMap->get($m))->c ?? 0);
        })->toArray();

        // Monthly aid distribution (sum of expenses+disbursements)
        $aidDist = FinancialManagement::selectRaw('DATE_FORMAT(date, "%Y-%m") as ym, SUM(amount) as total')
            ->whereIn('transaction_type', ['Expense', 'Disbursement'])
            ->where('date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();
        $aidMap = collect($aidDist)->keyBy('ym');
        $data['aidDistribution'] = $months->map(function ($m) use ($aidMap) {
            return (int) (optional($aidMap->get($m))->total ?? 0);
        })->toArray();

        // Simple activity logs (recent user signups)
        $data['recentUsers'] = DB::table('users')
            ->leftJoin('organizations', 'users.organization_id', '=', 'organizations.id')
            ->whereNotNull('users.organization_id')
            ->orderByDesc('users.id')
            ->take(10)
            ->select('users.id','users.name','users.email','users.organization_id','users.created_at','organizations.organization_name as org_name')
            ->get();

        return view('admin.dashboard')->with($data);
    }

    public function organizations(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->isSystemAdmin()) {
            return redirect()->route('cms.dashboard.index')->with('error', 'Unauthorized: Super Admin access required.');
        }

        $orgs = Organization::orderBy('id', 'desc')->get();
        $rows = [];
        foreach ($orgs as $org) {
            $rows[] = [
                'id' => $org->id,
                'name' => $org->organization_name,
                'email' => $org->email,
                'domain' => $org->domain ?? null,
                'families' => Family::where('organization_id', $org->id)->count(),
                'volunteers' => Volunteer::where('organization_id', $org->id)->count(),
                'aid_programs' => AidProgram::where('organization_id', $org->id)->count(),
                'surveys' => Surveys::where('organization_id', $org->id)->count(),
                'donations' => (int) FinancialManagement::where('organization_id', $org->id)->where('transaction_type', 'Donation')->sum('amount'),
                'expenses' => (int) FinancialManagement::where('organization_id', $org->id)->whereIn('transaction_type', ['Expense', 'Disbursement'])->sum('amount'),
            ];
        }

        return view('admin.organizations')->with(['rows' => $rows]);
    }

    public function invoice(Request $request, Organization $organization)
    {
        $user = $request->user();
        if (! $user || ! $user->isSystemAdmin()) {
            return redirect()->route('cms.dashboard.index')->with('error', 'Unauthorized: Super Admin access required.');
        }

        $data = [];
        $data['organization'] = $organization;
        $data['families'] = Family::where('organization_id', $organization->id)->count();
        $data['volunteers'] = Volunteer::where('organization_id', $organization->id)->count();
        $data['aidPrograms'] = AidProgram::where('organization_id', $organization->id)->count();
        $data['surveys'] = Surveys::where('organization_id', $organization->id)->count();
        $data['donationsTotal'] = (int) FinancialManagement::where('organization_id', $organization->id)->where('transaction_type', 'Donation')->sum('amount');
        $data['expensesTotal'] = (int) FinancialManagement::where('organization_id', $organization->id)->whereIn('transaction_type', ['Expense', 'Disbursement'])->sum('amount');
        $data['netBalance'] = $data['donationsTotal'] - $data['expensesTotal'];

        return view('admin.organization-invoice')->with($data);
    }

    public function users(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->isSystemAdmin()) {
            return redirect()->route('cms.dashboard.index')->with('error', 'Unauthorized: Super Admin access required.');
        }

        $rows = DB::table('users')
            ->join('organizations', 'users.organization_id', '=', 'organizations.id')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->whereNotNull('users.organization_id')
            ->whereNull('users.deleted_at')
            ->select(
                'users.id', 'users.name', 'users.email', 'users.is_admin',
                'organizations.organization_name as org_name', 'organizations.id as org_id',
                DB::raw('MAX(CASE WHEN roles.is_system = 1 THEN 1 ELSE 0 END) as is_system_admin')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.is_admin', 'organizations.organization_name', 'organizations.id')
            ->orderBy('organizations.organization_name')
            ->orderBy('users.name')
            ->get();

        return view('admin.users')->with(['rows' => $rows]);
    }
}
