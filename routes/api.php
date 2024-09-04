<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UbigeoController;
use App\Http\Controllers\Api\IpressController;
use App\Http\Controllers\Api\Cie10Controller;
use App\Http\Controllers\Api\DiagnosticController;
use App\Http\Controllers\Api\MedicamentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    //Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::apiResource('/permissions', PermissionController::class);
    Route::apiResource('/roles', RoleController::class);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/cie10', Cie10Controller::class);
    Route::apiResource('/diagnostic', DiagnosticController::class);
    Route::apiResource('/medicament', MedicamentController::class);

    //ruta para pacientes
    Route::get('/patients', [UserController::class, 'getPatients']);
    Route::post('/patients', [UserController::class, 'storePatient']);

    //ruta para seguimiento al paciente
    Route::get('/patients/follow', [UserController::class, 'getPatientsForFollow']);

    //rutas para ubigeo Departamento, Provincia y Distrito
    Route::get('/get-departments', [UbigeoController::class, 'getDepartments']);
    Route::get('/get-provinces/{department_id}', [UbigeoController::class, 'getProvinces']);
    Route::get('/get-districts/{province_id}', [UbigeoController::class, 'getDistricts']);

    //rutas para ipress getIpressByUbigeo
    Route::get('/get-ipress-by-ubigeo/{ubigeo}', [IpressController::class, 'getIpressByUbigeo']);

    Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions']);
});
