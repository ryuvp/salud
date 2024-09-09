<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cie10 extends Model
{
    use HasFactory;

    protected $guard_name = 'api';

    protected $table = 'cie10';

    public function diagnostics()
    {
        return $this->hasMany(Diagnostic::class);
    }
}
