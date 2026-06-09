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
            // Local: use base domain with subdomain parameter
            $resetUrl = 'http://localhost:8000/password/reset/'.$this->token.'?email='.urlencode($this->user->email);
            if ($org && $org->domain) {
                $resetUrl .= '&subdomain='.$org->domain;
            }
        } else {
            $base = config('app.url');
            $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
            $host = parse_url($base, PHP_URL_HOST) ?: 'api.reliowork.com';
            $tenantHost = $host;
            if ($org && $org->domain) {
                // Temporary: use base domain with subdomain parameter (until DNS is fixed)
                $resetUrl = $scheme.'://'.$host.'/password/reset/'.$this->token.'?email='.urlencode($this->user->email).'&subdomain='.$org->domain;
            } else {
                $resetUrl = $scheme.'://'.$host.'/password/reset/'.$this->token.'?email='.urlencode($this->user->email);
            }
        }

        return $this->subject('Reset Your Password')
            ->view('emails.password-reset')
            ->with([
                'user' => $this->user,
                'resetUrl' => $resetUrl,
            ]);
    }
}
