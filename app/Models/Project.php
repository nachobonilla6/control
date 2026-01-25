<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'type', 'video_url', 'active', 'images'];

    protected $casts = [
        'images' => 'array',
        'active' => 'boolean'
    ];
}
