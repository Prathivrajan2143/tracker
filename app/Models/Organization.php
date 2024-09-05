<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'domain_name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

     /**
     * Get the users for the organization.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    /**
     * Get the authentication tokens for the organization.
     */
    public function temporaryCrediantials()
    {
        return $this->hasMany(TemporaryCredential::class, 'organization_id');
    }
    
}
