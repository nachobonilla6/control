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
        'status',
        'facebook_account_id',
    ];

    protected $casts = [
        // 'post_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(FacebookAccount::class, 'facebook_account_id');
    }
}
