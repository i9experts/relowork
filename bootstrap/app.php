<?php

use App\Http\Middleware\InactiveLogout;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\TenantContextMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->call(function () {
            $from = now()->subWeek()->startOfWeek();
            $to = now()->subWeek()->endOfWeek();

            $activeOrganizations = \App\Models\Organization::where('active_status', true)->count();
            $donations = (int) \App\Models\FinancialManagement::where('transaction_type', 'Donation')
                ->whereBetween('date', [$from->toDateString(), $to->toDateString()])->sum('amount');
            $aidDistributed = (int) \App\Models\FinancialManagement::whereIn('transaction_type', ['Expense', 'Disbursement'])
                ->whereBetween('date', [$from->toDateString(), $to->toDateString()])->sum('amount');
            $newFamilies = (int) \App\Models\Family::whereBetween('created_at', [$from, $to])->count();
            $pendingApprovals = (int) \App\Models\FinancialManagement::whereNull('approved_by')
                ->whereBetween('date', [$from->toDateString(), $to->toDateString()])->count();

            $digest = [
                'active_orgs' => $activeOrganizations,
                'donations_total' => $donations,
                'disbursements_total' => $aidDistributed,
                'new_families' => $newFamilies,
                'pending_approvals' => $pendingApprovals,
                'top_kpis' => 'Net Balance: '.number_format($donations - $aidDistributed).' • Period: '.$from->format('M d').'—'.$to->format('M d, Y'),
                'actionUrl' => route('cms.admin.dashboard'),
            ];

            try { \App\Mail\SystemNotificationMailer::sendWeeklyDigest($digest); } catch (\Throwable $e) {
                try { \App\Mail\SystemNotificationMailer::sendSystemErrorAlert(['message' => 'Weekly digest send failed', 'context' => $e->getMessage()]); } catch (\Throwable $e2) {}
            }
        })->weeklyOn(1, '07:00');

        $schedule->call(function () {
            $today = now()->startOfDay();
            $tenDays = $today->copy()->addDays(10)->toDateString();
            $oneDay = $today->copy()->addDay()->toDateString();

            $orgsTen = \App\Models\Organization::whereNotNull('subscription_expires_at')
                ->whereDate('subscription_expires_at', $tenDays)->get();
            foreach ($orgsTen as $org) {
                try {
                    $org->billing_status = 'expiring_10';
                    $org->save();
                } catch (\Throwable $e) {}
                try { \App\Mail\SystemNotificationMailer::sendBillingAlert($org, ['reason' => 'Plan expiring in 10 days']); } catch (\Throwable $e) {}
                try { \App\Mail\NotificationMailer::sendOrgBillingAlert($org->id, ['reason' => 'Plan expiring in 10 days']); } catch (\Throwable $e) {}
            }

            $orgsOne = \App\Models\Organization::whereNotNull('subscription_expires_at')
                ->whereDate('subscription_expires_at', $oneDay)->get();
            foreach ($orgsOne as $org) {
                try {
                    $org->billing_status = 'expiring_1';
                    $org->save();
                } catch (\Throwable $e) {}
                try { \App\Mail\SystemNotificationMailer::sendBillingAlert($org, ['reason' => 'Plan expiring in 24 hours']); } catch (\Throwable $e) {}
                try { \App\Mail\NotificationMailer::sendOrgBillingAlert($org->id, ['reason' => 'Plan expiring in 24 hours']); } catch (\Throwable $e) {}
            }

            $expired = \App\Models\Organization::whereNotNull('subscription_expires_at')
                ->whereDate('subscription_expires_at', '<', $today->toDateString())
                ->where('active_status', true)
                ->get();
            foreach ($expired as $org) {
                try {
                    $org->active_status = false;
                    $org->billing_status = 'expired';
                    $org->save();
                } catch (\Throwable $e) {}
                try { \App\Mail\SystemNotificationMailer::sendOrganizationDeactivated($org, ['reason' => 'Plan expired', 'changedBy' => null]); } catch (\Throwable $e) {}
                try { \App\Mail\SystemNotificationMailer::sendBillingAlert($org, ['reason' => 'Plan expired – organization deactivated']); } catch (\Throwable $e) {}
                try { \App\Mail\NotificationMailer::sendOrgBillingAlert($org->id, ['reason' => 'Your plan has expired; organization deactivated']); } catch (\Throwable $e) {}
            }
        })->dailyAt('03:00');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', InactiveLogout::class);
        $middleware->appendToGroup('web', TenantContextMiddleware::class);

        // Route middleware aliases
        $middleware->alias([
            'permission' => PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
