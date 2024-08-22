<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Organization extends Model
{
    // Specify the table associated with the model
    protected $table = 'organizations';

    // Specify the primary key type and name
    protected $primaryKey = 'org_id';
    protected $keyType = 'string';
    public $incrementing = false;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'org_id',
        'org_name',
        'org_admin_email',
        'org_domain_name',
        'temporary_password',
        'password_expires_at',
    ];

    // Define attributes that should be cast to native types
    protected $casts = [
        'org_id' => 'string',
    ];

    // Optionally, define the timestamp format
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Get the value of the `org_id` attribute.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function getOrgIdAttribute($value)
    {
        return (string) $value;
    }

    /**
     * Automatically set the UUID for the `org_id` attribute.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($organizations) {
            if (empty($organizations->org_id)) {
                $organizations->org_id = (string) Str::uuid();
            }
        });
    }
}
