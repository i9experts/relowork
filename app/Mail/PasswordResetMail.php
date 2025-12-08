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
        $base = config('app.url');
        $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'http';
        $host = parse_url($base, PHP_URL_HOST) ?: 'localhost';
        $port = parse_url($base, PHP_URL_PORT);
        $org = $this->user->organization_id ? \App\Models\Organization::find($this->user->organization_id) : null;
        $tenantHost = $host;
        if ($org && $org->domain) {
            if ($host === 'localhost' || str_ends_with($host, '.localhost')) {
                $tenantHost = $org->domain.'.localhost';
            } else {
                $tenantHost = $org->domain.'.'.$host;
            }
        }
        $portSuffix = $port ? (':'.$port) : '';
        $resetUrl = $scheme.'://'.$tenantHost.$portSuffix.'/password/reset/'.$this->token.'?email='.urlencode($this->user->email);

        return $this->subject('Reset Your Password')
            ->view('emails.password-reset')
            ->with([
                'user' => $this->user,
                'loginUrl' => $resetUrl,
            ]);
    }
}
