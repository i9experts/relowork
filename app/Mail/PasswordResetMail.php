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
        // Build password reset URL that matches PasswordResetController@showResetForm
        $resetUrl = url('/password/reset/'.$this->token.'?email='.urlencode($this->user->email));

        return $this->subject('Reset Your Password')
            ->view('emails.password-reset')
            ->with([
                'user'      => $this->user,
                'loginUrl'  => $resetUrl,
            ]);
    }
}
