<?php

namespace App\Http\Controllers;

use App\Models\AidProgram;
use App\Models\Family;
use App\Models\FinancialManagement;
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

        if (!$u || $u->hasPermission('families')) {
            $familiesQ = Family::query();
            if (!$isSuper) { $familiesQ->where('organization_id', $organizationId); }
            $families = $familiesQ
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();
            foreach ($families as $f) {
                $items[] = [
                    'title' => 'Family #'.$f->id,
                    'desc' => ($f->family_name ?: $f->head_of_family_name ?: '-'),
                    'time' => optional($f->updated_at)->diffForHumans(),
                    'ts' => optional($f->updated_at)->timestamp,
                    'badge' => 'info',
                    'badgeLabel' => 'Family',
                    'href' => route('cms.family.show', $f->id),
                    'type' => 'other',
                ];
            }
        }

        if (!$u || $u->hasPermission('aid_programs')) {
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

        if (!$u || $u->hasPermission('financial')) {
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

        if (!$u || $u->hasPermission('volunteers')) {
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

