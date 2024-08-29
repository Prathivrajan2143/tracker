<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationsLoginPage extends Model
{
    use HasFactory;

    protected $fillable = ['org_id', 'logo', 'welcomeQuote'];
}