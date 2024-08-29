<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationLoginSetup extends Model
{
    // Define the table name if it's not the plural form of the model name
    protected $table = 'organization_login_setup';

    // Fillable properties to allow mass assignment
    protected $fillable = ['login_type', 'email'];

    // Define the relationship with OrganizationSsoCrediantials
    public function ssoCredentials()
    {
        return $this->hasOne(OrganizationSsoCrediantials::class, 'login_setup_id');
    }
}
