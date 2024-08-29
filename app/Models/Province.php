<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $guard_name = 'api';
    protected $table = 'province';

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id');
    }
}
