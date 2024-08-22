<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizationInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $inviteUrl;
    public $temporaryPassword;

    public function __construct($inviteUrl, $temporaryPassword)
    {
        $this->inviteUrl = $inviteUrl;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function build()
    {
        return $this->view('emails.organization_invite')
                    ->with([
                        'inviteUrl' => $this->inviteUrl,
                        'temporaryPassword' => $this->temporaryPassword,
                    ]);
    }
}



