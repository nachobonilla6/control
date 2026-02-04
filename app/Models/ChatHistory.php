<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    use HasFactory;

    protected $table = 'josh_dev_chat_history';

    // Disable default timestamps since we use a custom 'created_at' and no 'updated_at'
    public $timestamps = false;

    protected $fillable = [
        'chat_id',
        'username',
        'role',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
