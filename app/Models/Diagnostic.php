<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    use HasFactory;

    protected $guard_name = 'api';
    protected $table = 'diagnostic';

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function prescriptions(){
        return $this->hasMany(Prescription::class, 'diagnostic_id');
    }
    public function cie10(){
        return $this->belongsTo(Cie10::class, 'cie10_id');
    }
    public function ipress(){
        return $this->belongsTo(Ipress::class, 'ipress_id');
    }
}
