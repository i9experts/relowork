<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationMailer
{
    public static function getOrgSettings(?int $organizationId): ?Setting
    {
        if (! $organizationId) {
            return null;
        }
        return Setting::where('organization_id', $organizationId)->first();
    }

    public static function orgAdmins(int $organizationId)
    {
        return User::where('organization_id', $organizationId)->where('is_admin', true)->get();
    }

    public static function sendAidApprovalAlert(int $organizationId, array $data = []): void
    {
        $settings = self::getOrgSettings($organizationId);
        if (! $settings || ! $settings->aid_approval_alerts) {
            return;
        }
        $admins = self::orgAdmins($organizationId);
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.aid-approval-alerts', ['user' => $admin, 'actionUrl' => $data['actionUrl'] ?? route('cms.financial-management.index')], function ($m) use ($admin) {
                    $m->to($admin->email)->subject('Aid Approval Required');
                });
            } catch (\Throwable $e) {
            }
        }
    }

    public static function sendEmailNotification(int $organizationId, array $data = []): void
    {
        $settings = self::getOrgSettings($organizationId);
        if (! $settings || ! $settings->email_notifications) {
            return;
        }
        $admins = self::orgAdmins($organizationId);
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.email-notifications', ['user' => $admin, 'actionUrl' => $data['actionUrl'] ?? route('cms.dashboard.index')], function ($m) use ($admin) {
                    $m->to($admin->email)->subject('New Activity Notification');
                });
            } catch (\Throwable $e) {
                Log::error('org email notification failed', ['admin' => $admin->id ?? null, 'error' => $e->getMessage()]);
            }
        }
    }

    public static function sendPushEmail(int $organizationId, array $data = []): void
    {
        $settings = self::getOrgSettings($organizationId);
        if (! $settings || ! $settings->push_notifications) {
            return;
        }
        $admins = self::orgAdmins($organizationId);
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.push-notifications', ['user' => $admin, 'actionUrl' => $data['actionUrl'] ?? route('cms.dashboard.index')], function ($m) use ($admin) {
                    $m->to($admin->email)->subject('New Notification');
                });
            } catch (\Throwable $e) {
            }
        }
    }

    public static function sendOrgBillingAlert(int $organizationId, array $data = []): void
    {
        $admins = self::orgAdmins($organizationId);
        $org = \App\Models\Organization::find($organizationId);
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.billing.billing-alert', [
                    'org' => $org,
                    'reason' => $data['reason'] ?? null,
                    'actionUrl' => $data['actionUrl'] ?? route('cms.setting.index'),
                ], function ($m) use ($admin) {
                    $m->to($admin->email)->subject('Billing / Subscription Alert');
                });
            } catch (\Throwable $e) {
            }
        }
    }
} // <-- ye single closing brace class ke liye
