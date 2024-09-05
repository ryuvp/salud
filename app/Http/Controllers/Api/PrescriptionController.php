<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Medicament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'diagnostic.id' => 'required|exists:diagnostic,id', // Verifica que el diagnóstico exista
            'medicament' => 'required', // El medicamento es obligatorio
        ]);

        // Si la validación falla, retorna un error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Inicia la transacción
        DB::beginTransaction();

        try {
            // Verifica si el medicamento viene con un id o como texto
            $medicament = null;

            if (is_array($request->medicament) && isset($request->medicament['id'])) {
                // Caso donde el medicamento se recibe con un id
                $medicament = Medicament::find($request->medicament['id']);
            
                if (!$medicament) {
                    return response()->json(['error' => 'Medicament not found.'], 404);
                }
            } else {
                // Obtener el nombre del medicamento, ya sea del string o del array
                $medicamentName = is_string($request->medicament) ? $request->medicament : $request->medicament['name'];
            
                // Buscar un medicamento existente con el mismo nombre (ignorando mayúsculas/minúsculas)
                $medicament = Medicament::where('name', $medicamentName)->first();
            
                if (!$medicament) {
                    // Crear el medicamento si no existe
                    $medicament = Medicament::create(['name' => $medicamentName]);
                }
            }
            // Formatear la fecha de inicio
            $star_date = null;
            if ($request->start_date) {
                $star_date = Carbon::parse($request->start_date)->format('Y-m-d');
            }

            // Crear la receta (prescripción)
            $prescription = Prescription::create([
                'diagnostic_id' => $request->diagnostic['id'],
                'medicament_id' => $medicament->id,
                'quantity' => $request->quantity?:null,
                'frequency' => $request->frequency?:null,
                'start_date' => $star_date,
            ]);

            // Confirma la transacción
            DB::commit();

            // Retorna una respuesta exitosa
            return response()->json(['message' => 'Prescription created successfully', 'data' => $prescription], 201);
        } catch (\Exception $e) {
            // Revierte la transacción en caso de error
            DB::rollBack();

            // Retorna un error con el mensaje de la excepción
            return response()->json(['error' => 'Failed to create prescription', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         // Iniciar la transacción
         DB::beginTransaction();

         try {
             // Buscar la prescripción por su ID
             $prescription = Prescription::find($id);
 
             // Verificar si la prescripción existe
             if (!$prescription) {
                 return response()->json(['status' => 'error', 'error' => 'Prescription not found.'], 404);
             }
 
             // Eliminar (soft delete) la prescripción
             $prescription->delete();
 
             // Confirmar la transacción
             DB::commit();
 
             // Retornar respuesta exitosa
             return response()->json(['status' => 'success', 'message' => 'Prescription deleted successfully.'], 200);
         } catch (\Exception $e) {
             // Revertir la transacción en caso de error
             DB::rollBack();
 
             // Retornar un error con el mensaje de la excepción
             return response()->json(['error' => 'Failed to delete prescription', 'message' => $e->getMessage()], 500);
         }
    }
}
