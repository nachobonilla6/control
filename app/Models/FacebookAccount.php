<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookAccount extends Model
{
    protected $fillable = [
        'name',
        'link',
        'page_id',
        'access_token',
    ];
}
