<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'email', 'location', 'phone', 'industry', 'status', 'alpha', 'website'];
}
