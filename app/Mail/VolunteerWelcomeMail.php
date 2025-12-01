<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VolunteerWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public string $plainPassword;

    public string $loginUrl;

    public function __construct(User $user, string $plainPassword, string $loginUrl)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = $loginUrl;
    }

    public function build(): self
    {
        return $this->subject('Your Volunteer Account Details')
            ->view('emails.volunteer-welcome');
    }
}
