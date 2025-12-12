<?php

namespace App\Http\Controllers;

use App\Models\AidProgram;
use App\Models\Family;
use App\Models\FinancialManagement;
use App\Models\Surveys;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function feed(Request $request)
    {
        $u = $request->user();
        $organizationId = optional($u)->organization_id ?? session('organization_id');
        $since = (int) $request->query('since', 0);

        $items = [];

        $isSuper = ($u && (method_exists($u,'isSystemAdmin') && $u->isSystemAdmin()));

        if ($isSuper) {
            $orgRecent = \App\Models\Organization::orderByDesc('created_at')->limit(5)->get();
            foreach ($orgRecent as $org) {
                $items[] = [
                    'title' => 'Organization Registered',
                    'desc' => ($org->organization_name ?: '-'),
                    'time' => optional($org->created_at)->diffForHumans(),
                    'ts' => optional($org->created_at)->timestamp,
                    'badge' => 'success',
                    'badgeLabel' => 'Registered',
                    'href' => route('cms.admin.organizations'),
                    'type' => 'other',
                ];
            }

            $orgActivated = \App\Models\Organization::where('active_status', true)->orderByDesc('updated_at')->limit(5)->get();
            foreach ($orgActivated as $org) {
                $items[] = [
                    'title' => 'Organization Activated',
                    'desc' => ($org->organization_name ?: '-'),
                    'time' => optional($org->updated_at)->diffForHumans(),
                    'ts' => optional($org->updated_at)->timestamp,
                    'badge' => 'primary',
                    'badgeLabel' => 'Activated',
                    'href' => route('cms.admin.organizations'),
                    'type' => 'other',
                ];
            }

            $orgDeactivated = \App\Models\Organization::where('active_status', false)->orderByDesc('updated_at')->limit(5)->get();
            foreach ($orgDeactivated as $org) {
                $items[] = [
                    'title' => 'Organization Deactivated',
                    'desc' => ($org->organization_name ?: '-'),
                    'time' => optional($org->updated_at)->diffForHumans(),
                    'ts' => optional($org->updated_at)->timestamp,
                    'badge' => 'danger',
                    'badgeLabel' => 'Deactivated',
                    'href' => route('cms.admin.organizations'),
                    'type' => 'other',
                ];
            }

            $billingAlert = \App\Models\Organization::whereNotNull('subscription_expires_at')
                ->where('subscription_expires_at', '<', now())
                ->orderByDesc('subscription_expires_at')
                ->limit(5)
                ->get();
            foreach ($billingAlert as $org) {
                $items[] = [
                    'title' => 'Billing Alert',
                    'desc' => ($org->organization_name ?: '-'),
                    'time' => optional($org->subscription_expires_at)->diffForHumans(),
                    'ts' => optional($org->subscription_expires_at)->timestamp,
                    'badge' => 'warning',
                    'badgeLabel' => 'Billing',
                    'href' => route('cms.admin.billing.settings'),
                    'type' => 'other',
                ];
            }

            $threshold = (int) (env('HIGH_VALUE_TX_THRESHOLD', 100000));
            $hvTx = FinancialManagement::where('amount', '>=', $threshold)
                ->orderByDesc('date')
                ->limit(5)
                ->get();
            foreach ($hvTx as $t) {
                $items[] = [
                    'title' => 'High-Value Transaction',
                    'desc' => ($t->transaction_type.' — '.(string)($t->amount)),
                    'time' => optional($t->date)->diffForHumans(),
                    'ts' => optional($t->date)->timestamp,
                    'badge' => 'secondary',
                    'badgeLabel' => ($t->transaction_type ?: 'Transaction'),
                    'href' => route('cms.admin.billing.history'),
                    'type' => 'other',
                ];
            }

            usort($items, function ($a, $b) {
                return (int)($b['ts'] ?? 0) <=> (int)($a['ts'] ?? 0);
            });
            $message = array_values(array_filter($items, function ($i) { return $i['type'] === 'message'; }));
            $other = array_values(array_filter($items, function ($i) { return $i['type'] === 'other'; }));
            $all = array_slice($items, 0, 12);
            $unread = array_values(array_filter($all, function ($i) use ($since) { return (int)($i['ts'] ?? 0) > $since; }));

            return response()->json([
                'all' => $all,
                'message' => $message,
                'other' => $other,
                'counts' => [
                    'all' => count($all),
                    'message' => count($message),
                    'other' => count($other),
                    'unread' => count($unread),
                ],
                'lastUpdated' => now()->diffForHumans(),
                'nowTs' => now()->getTimestamp(),
            ]);
        }

        if ($isSuper || !$u || $u->hasPermission('families')) {
            $familiesQ = Family::query();
            if (!$isSuper) { $familiesQ->where('organization_id', $organizationId); }
            $families = $familiesQ
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();
            foreach ($families as $f) {
                $items[] = [
                    'title' => 'Family',
                    'desc' => ($f->family_code ?: ($f->head_of_family_name ?: '-')),
                    'time' => optional($f->updated_at)->diffForHumans(),
                    'ts' => optional($f->updated_at)->timestamp,
                    'badge' => 'info',
                    'badgeLabel' => 'Family',
                    'href' => route('cms.family.show', $f->id),
                    'type' => 'other',
                ];
            }
        }

        if ($isSuper || !$u || $u->hasPermission('aid_programs')) {
            $programsQ = AidProgram::query();
            if (!$isSuper) { $programsQ->where('organization_id', $organizationId); }
            $programs = $programsQ
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();
            foreach ($programs as $p) {
                $items[] = [
                    'title' => 'Aid Program',
                    'desc' => ($p->program_title ?: '-'),
                    'time' => optional($p->updated_at)->diffForHumans(),
                    'ts' => optional($p->updated_at)->timestamp,
                    'badge' => 'primary',
                    'badgeLabel' => ($p->status ?: 'Updated'),
                    'href' => route('cms.aid-program.show', $p->id),
                    'type' => 'other',
                ];
            }
        }

        if ($isSuper || !$u || $u->hasPermission('financial')) {
            $txQ = FinancialManagement::query();
            if (!$isSuper) { $txQ->where('organization_id', $organizationId); }
            $tx = $txQ
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
            foreach ($tx as $t) {
                $items[] = [
                    'title' => 'Transaction',
                    'desc' => ($t->transaction_type.' — '.(string)($t->amount)),
                    'time' => optional($t->created_at)->diffForHumans(),
                    'ts' => optional($t->created_at)->timestamp,
                    'badge' => ($t->transaction_type === 'Expense' ? 'danger' : ($t->transaction_type === 'Donation' ? 'secondary' : ($t->transaction_type === 'Disbursement' ? 'warning' : 'info'))),
                    'badgeLabel' => ($t->transaction_type ?: 'Updated'),
                    'href' => route('cms.financial-management.show', $t->id),
                    'type' => 'other',
                ];
            }
        }

        if ($isSuper || !$u || $u->hasPermission('volunteers')) {
            $volunteersQ = Volunteer::query();
            if (!$isSuper) { $volunteersQ->where('organization_id', $organizationId); }
            $volunteers = $volunteersQ
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();
            foreach ($volunteers as $v) {
                $items[] = [
                    'title' => 'Volunteer',
                    'desc' => ($v->full_name ?: optional($v->user)->name ?: '-'),
                    'time' => optional($v->updated_at)->diffForHumans(),
                    'ts' => optional($v->updated_at)->timestamp,
                    'badge' => ($v->status === 'Active' ? 'success' : 'warning'),
                    'badgeLabel' => ($v->status ?: 'Updated'),
                    'href' => route('cms.volunteer.show', $v->id),
                    'type' => 'message',
                ];
            }
        }

        if ($isSuper || !$u || $u->hasPermission('surveys')) {
            $surveysQ = Surveys::query();
            if (!$isSuper) { $surveysQ->where('organization_id', $organizationId); }
            $surveys = $surveysQ
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();
            foreach ($surveys as $s) {
                $items[] = [
                    'title' => 'Survey',
                    'desc' => ($s->survey_code ?: ('#'.$s->id)),
                    'time' => optional($s->updated_at)->diffForHumans(),
                    'ts' => optional($s->updated_at)->timestamp,
                    'badge' => ($s->revisit_flag ? 'success' : 'light'),
                    'badgeLabel' => ($s->revisit_flag ? 'Active' : 'Completed'),
                    'href' => route('cms.survey.show', $s->id),
                    'type' => 'other',
                ];
            }
        }

        usort($items, function ($a, $b) {
            return (int)($b['ts'] ?? 0) <=> (int)($a['ts'] ?? 0);
        });

        $message = array_values(array_filter($items, function ($i) { return $i['type'] === 'message'; }));
        $other = array_values(array_filter($items, function ($i) { return $i['type'] === 'other'; }));
        $all = array_slice($items, 0, 12);
        $unread = array_values(array_filter($all, function ($i) use ($since) { return (int)($i['ts'] ?? 0) > $since; }));

        return response()->json([
            'all' => $all,
            'message' => $message,
            'other' => $other,
            'counts' => [
                'all' => count($all),
                'message' => count($message),
                'other' => count($other),
                'unread' => count($unread),
            ],
            'lastUpdated' => now()->diffForHumans(),
            'nowTs' => now()->getTimestamp(),
        ]);
    }
}
