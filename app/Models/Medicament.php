<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    use HasFactory;

    protected $guard_name = 'api';

    protected $table = 'medicament';

    protected $fillable = ['name'];
}
