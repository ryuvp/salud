<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ipress extends Model
{
    use HasFactory;
    protected $guard_name = 'api';
    //table name
    protected $table = 'ipress';

    public function users()
    {
        return $this->hasMany(User::class, 'ipress_id');
    }

    public function diagnostics()
    {
        return $this->hasMany(Diagnostic::class, 'ipress_id');
    }
}
