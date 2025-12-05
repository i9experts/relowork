<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected function buildIndex(Request $request): array
    {
        $u = $request->user();
        $items = [];

        if ($u && (method_exists($u, 'isSystemAdmin') && $u->isSystemAdmin())) {
            $items[] = ['label' => 'Admin Dashboard', 'href' => route('cms.admin.dashboard')];
            $items[] = ['label' => 'Organizations', 'href' => route('cms.admin.organizations')];
            $items[] = ['label' => 'Users', 'href' => route('cms.admin.users')];
            $items[] = ['label' => 'Billing History', 'href' => route('cms.admin.billing.history')];
            $items[] = ['label' => 'Billing Settings', 'href' => route('cms.admin.billing.settings')];
            $items[] = ['label' => 'New Organization', 'href' => route('organization.register')];
        } else {
            if (!$u || $u->hasPermission('dashboard')) {
                $items[] = ['label' => 'Dashboard', 'href' => route('cms.dashboard.index')];
            }
            if (!$u || $u->hasPermission('families')) {
                $items[] = ['label' => 'Families', 'href' => route('cms.family.index')];
            }
            if (!$u || $u->hasPermission('aid_programs')) {
                $items[] = ['label' => 'Aid Programs', 'href' => route('cms.aid-program.index')];
            }
            if (!$u || $u->hasPermission('financial')) {
                $items[] = ['label' => 'Financials', 'href' => route('cms.financial-management.index')];
            }
            if (!$u || $u->hasPermission('surveys')) {
                $items[] = ['label' => 'Surveys', 'href' => route('cms.survey.index')];
            }
            if (!$u || $u->hasPermission('reports')) {
                $items[] = ['label' => 'Reports', 'href' => route('cms.report.index')];
            }
            if (!$u || $u->hasPermission('volunteers')) {
                $items[] = ['label' => 'Volunteers', 'href' => route('cms.volunteer.index')];
            }
            if (!$u || $u->hasPermission('settings')) {
                $items[] = ['label' => 'Settings', 'href' => route('cms.setting.index')];
            }
            $items[] = ['label' => 'Profile Settings', 'href' => route('cms.profile.index')];
        }

        return $items;
    }

    public function suggest(Request $request)
    {
        $q = strtolower(trim((string) $request->query('q', '')));
        $index = $this->buildIndex($request);

        if ($q === '') {
            return response()->json(['suggestions' => []]);
        }

        $scored = [];
        foreach ($index as $item) {
            $key = strtolower($item['label']);
            $pos = strpos($key, $q);
            if ($pos === false) {
                continue;
            }
            $score = ($pos === 0 ? 0 : 1) + (strlen($key) - strlen($q)) * 0.001;
            $scored[] = ['item' => $item, 'score' => $score];
        }

        usort($scored, function ($a, $b) { return $a['score'] <=> $b['score']; });

        $out = array_map(function ($s) { return $s['item']; }, array_slice($scored, 0, 8));

        return response()->json(['suggestions' => $out]);
    }

    public function search(Request $request)
    {
        $q = strtolower(trim((string) $request->query('q', '')));
        $index = $this->buildIndex($request);

        if ($q === '') {
            $u = $request->user();
            if ($u && (method_exists($u, 'isSystemAdmin') && $u->isSystemAdmin())) {
                return redirect()->route('cms.admin.dashboard');
            }
            return redirect()->route('cms.dashboard.index');
        }

        $best = null;
        $bestScore = PHP_FLOAT_MAX;
        foreach ($index as $item) {
            $key = strtolower($item['label']);
            $pos = strpos($key, $q);
            if ($pos === false) {
                continue;
            }
            $score = ($pos === 0 ? 0 : 1) + (strlen($key) - strlen($q)) * 0.001;
            if ($score < $bestScore) {
                $bestScore = $score;
                $best = $item;
            }
        }

        if ($best && isset($best['href'])) {
            return redirect()->to($best['href']);
        }

        return redirect()->back()->with('warning', 'No matching page found.');
    }
}

