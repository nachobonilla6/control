<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    protected $fillable = [
        'content',
        'image1',
        'image2',
        'image3',
        'post_at',
    ];

    protected $casts = [
        'post_at' => 'datetime',
    ];
}
