<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public string $token;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $org = $this->user->organization_id ? \App\Models\Organization::find($this->user->organization_id) : null;

        if (app()->environment('local')) {
            $domainHost = 'localhost';
            $tenantHost = $domainHost;
            if ($org && $org->domain) {
                $tenantHost = $org->domain.'.localhost';
            }
            $resetUrl = 'http://'.$tenantHost.':8000/password/reset/'.$this->token.'?email='.urlencode($this->user->email);
        } else {
            $base = config('app.url');
            $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
            $host = parse_url($base, PHP_URL_HOST) ?: 'relowork.com';
            $tenantHost = $host;
            if ($org && $org->domain) {
                $tenantHost = $org->domain.'.'.$host;
            }
            $resetUrl = $scheme.'://'.$tenantHost.'/password/reset/'.$this->token.'?email='.urlencode($this->user->email);
        }

        return $this->subject('Reset Your Password')
            ->view('emails.password-reset')
            ->with([
                'user' => $this->user,
                'loginUrl' => $resetUrl,
            ]);
    }
}
