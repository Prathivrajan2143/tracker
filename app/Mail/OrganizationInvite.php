<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizationInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $inviteUrl;

    public function __construct($inviteUrl)
    {
        $this->inviteUrl = $inviteUrl;
    }

    public function build()
    {
        return $this->view('emails.organization_invite')
                    ->with(['inviteUrl' => $this->inviteUrl]);
    }
}
