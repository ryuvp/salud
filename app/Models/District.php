<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $guard_name = 'api';
    protected $table = 'district';

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
