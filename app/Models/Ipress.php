<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ipress extends Model
{
    use HasFactory;
    protected $guard_name = 'api';
    protected $table = 'ipress';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
