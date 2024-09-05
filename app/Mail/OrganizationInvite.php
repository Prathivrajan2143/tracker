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
    public $admin_name;

    public function __construct($inviteUrl, $temporaryPassword, $admin_name)
    {
        $this->inviteUrl = $inviteUrl;
        $this->temporaryPassword = $temporaryPassword;
        $this->admin_name = $admin_name;
    }

    public function build()
    {
        return $this->view('emails.organization_invite')
                    ->with([
                        'inviteUrl' => $this->inviteUrl,
                        'admin_name' => $this->admin_name,
                        'temporaryPassword' => $this->temporaryPassword,
                    ]);
    }
}



