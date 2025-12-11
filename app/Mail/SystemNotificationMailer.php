<?php

namespace App\Mail;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SystemNotificationMailer
{
    protected static function systemAdmins()
    {
        return User::whereHas('roles', function ($q) {
            $q->where('is_system', true);
        })->get();
    }

    public static function sendOrganizationRegistered(Organization $organization): void
    {
        $admins = self::systemAdmins();
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.organizations.organization-registered', [
                    'org' => $organization,
                    'ownerEmail' => $organization->email,
                    'actionUrl' => route('cms.admin.organizations'),
                ], function ($m) use ($admin) {
                    $m->to($admin->email)->subject('New Organization Registered');
                });
            } catch (\Throwable $e) { Log::error('system org registered mail failed', ['error' => $e->getMessage()]); }
        }
    }

    public static function sendOrganizationActivated(Organization $organization, array $data = []): void
    {
        $admins = self::systemAdmins();
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.organizations.organization-activated', [
                    'org' => $organization,
                    'changedBy' => $data['changedBy'] ?? null,
                    'actionUrl' => route('cms.admin.organizations'),
                ], function ($m) use ($admin) {
                    $m->to($admin->email)->subject('Organization Activated');
                });
            } catch (\Throwable $e) {}
        }
    }

    public static function sendOrganizationDeactivated(Organization $organization, array $data = []): void
    {
        $admins = self::systemAdmins();
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.organizations.organization-deactivated', [
                    'org' => $organization,
                    'reason' => $data['reason'] ?? null,
                    'changedBy' => $data['changedBy'] ?? null,
                    'actionUrl' => route('cms.admin.organizations'),
                ], function ($m) use ($admin) {
                    $m->to($admin->email)->subject('Organization Deactivated');
                });
            } catch (\Throwable $e) {}
        }
    }

    public static function sendBillingAlert(Organization $organization, array $data = []): void
    {
        $admins = self::systemAdmins();
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.billing.billing-alert', [
                    'org' => $organization,
                    'reason' => $data['reason'] ?? null,
                    'actionUrl' => route('cms.admin.organizations'),
                ], function ($m) use ($admin) {
                    $m->to($admin->email)->subject('Billing Alert');
                });
            } catch (\Throwable $e) {}
        }
    }

    public static function sendHighValueTransactionAlert(array $payload): void
    {
        $admins = self::systemAdmins();
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.security.high-value-transaction', $payload, function ($m) use ($admin) {
                    $m->to($admin->email)->subject('High-Value Transaction Alert');
                });
            } catch (\Throwable $e) {}
        }
    }

    public static function sendSystemErrorAlert(array $payload): void
    {
        $admins = self::systemAdmins();
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.system.system-error', $payload, function ($m) use ($admin) {
                    $m->to($admin->email)->subject('System Error');
                });
            } catch (\Throwable $e) { Log::error('system error mail failed', ['error' => $e->getMessage()]); }
        }
    }

    public static function sendWeeklyDigest(array $digest): void
    {
        $admins = self::systemAdmins();
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.digest.weekly-digest', $digest, function ($m) use ($admin) {
                    $m->to($admin->email)->subject('Weekly Global Digest');
                });
            } catch (\Throwable $e) { Log::error('weekly digest mail failed', ['error' => $e->getMessage()]); }
        }
    }

    public static function sendSecurityAlert(array $payload): void
    {
        $admins = self::systemAdmins();
        foreach ($admins as $admin) {
            try {
                Mail::send('emails.security.security-alert', $payload, function ($m) use ($admin) {
                    $m->to($admin->email)->subject('Security Alert');
                });
            } catch (\Throwable $e) { Log::error('security alert mail failed', ['error' => $e->getMessage()]); }
        }
    }
}
