<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name', 
        'email', 
        'email2',
        'location', 
        'phone', 
        'industry', 
        'status', 
        'alpha', 
        'website',
        'address',
        'language',
        'contact_name',
        'facebook',
        'instagram',
        'opening_hours',
        'notes',
        'last_email_sent_at'
    ];

    protected $casts = [
        'last_email_sent_at' => 'datetime',
    ];
}
