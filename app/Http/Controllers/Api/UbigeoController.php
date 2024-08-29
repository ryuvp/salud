<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Province;
use App\Models\District;
use Illuminate\Http\Request;

class UbigeoController extends Controller
{
    public function getDepartments(){
        $departamentos = Department::all();
        return response()->json($departamentos);
    }

    public function getProvinces($department_id){
        $provincias = Province::where('department_id', $department_id)->get();
        return response()->json($provincias);
    }

    public function getDistricts($province_id)
    {
        $distritos = District::where('province_id', $province_id)->get();
        return response()->json($distritos);
    }
}
