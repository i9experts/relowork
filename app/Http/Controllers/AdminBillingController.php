<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationPayment;
use Illuminate\Http\Request;

class AdminBillingController extends Controller
{
    protected function ensureSuperAdmin(): void
    {
        $u = auth()->user();
        if (! $u || ! (method_exists($u, 'isSystemAdmin') && $u->isSystemAdmin())) {
            abort(403);
        }
    }

    public function history(Request $request)
    {
        $this->ensureSuperAdmin();

        $query = OrganizationPayment::query()->with('organization');

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->integer('organization_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($w) use ($q) {
                $w->where('reference', 'like', "%{$q}%")
                  ->orWhere('method', 'like', "%{$q}%");
            });
        }
        if ($request->filled('from')) {
            $query->whereDate('paid_at', '>=', $request->date('from')->format('Y-m-d'));
        }
        if ($request->filled('to')) {
            $query->whereDate('paid_at', '<=', $request->date('to')->format('Y-m-d'));
        }

        $payments = $query->orderByDesc('paid_at')->orderByDesc('id')->paginate(20)->appends($request->query());
        $organizations = Organization::orderBy('organization_name')->get(['id','organization_name']);

        $hasFilters = $request->isMethod('post')
            || $request->filled('organization_id')
            || $request->filled('status')
            || $request->filled('q')
            || $request->filled('from')
            || $request->filled('to');

        return view('admin.billing.history', compact('payments', 'organizations', 'hasFilters'));
    }

    public function historyPrint(Request $request)
    {
        $this->ensureSuperAdmin();

        // Reuse the same filters
        $query = OrganizationPayment::query()->with('organization');
        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->integer('organization_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($w) use ($q) {
                $w->where('reference', 'like', "%{$q}%")
                  ->orWhere('method', 'like', "%{$q}%");
            });
        }
        if ($request->filled('from')) {
            $query->whereDate('paid_at', '>=', $request->date('from')->format('Y-m-d'));
        }
        if ($request->filled('to')) {
            $query->whereDate('paid_at', '<=', $request->date('to')->format('Y-m-d'));
        }
        $payments = $query->orderByDesc('paid_at')->orderByDesc('id')->get();

        return view('admin.billing.history-print', compact('payments'));
    }

    public function settings(Request $request)
    {
        $this->ensureSuperAdmin();
        $organizations = Organization::orderBy('organization_name')->paginate(25);
        return view('admin.billing.settings', compact('organizations'));
    }

    public function updateExpiry(Request $request, Organization $organization)
    {
        $this->ensureSuperAdmin();

        $data = $request->validate([
            'subscription_expires_at' => ['required', 'date'],
        ]);

        $organization->subscription_expires_at = $data['subscription_expires_at'];
        $organization->billing_status = 'active';
        $organization->active_status = true;
        $organization->save();

        return back()->with('success', 'Subscription expiry updated and status set to active.');
    }
}
