<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public Organization $organization;

    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function build(): self
    {
        return $this->subject('Your Organization Verification Code')
            ->view('emails.organization-otp');
    }
}
