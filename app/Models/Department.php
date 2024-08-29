<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $guard_name = 'api';
    protected $table = 'department';

    public function province()
    {
        return $this->hasMany(Province::class, 'department_id');
    }
}
