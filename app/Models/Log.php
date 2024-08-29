<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'operator_id',
        'title',
        'content',
    ];
}
