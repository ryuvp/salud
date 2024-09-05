<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $guard_name = 'api';

    protected $table = 'prescription';

    protected $fillable = [
        'diagnostic_id',
        'medicament_id',
    ];

    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class, 'diagnostic_id');
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class, 'medicament_id');
    }
}
