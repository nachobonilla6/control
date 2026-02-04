<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientStatus extends Model
{
    protected $fillable = ['name', 'label', 'color'];
    protected $table = 'client_statuses';
}
