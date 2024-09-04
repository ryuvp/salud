<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Diagnostic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiagnosticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'ipress_id' => 'required|exists:ipress,id',
            'cie10_id' => 'required|exists:cie10,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            $existingDiagnostic = Diagnostic::where('user_id', $request->user_id)
                ->where('cie10_id', $request->cie10_id)
                ->where('ipress_id', $request->ipress_id)
                ->first();
            if ($existingDiagnostic) {
                return response()->json(['error' => 'The user already has this diagnostic assigned.'], 409);
            }
            $diagnostic = Diagnostic::create([
                'user_id' => $request->user_id,
                'cie10_id' => $request->cie10_id,
                'ipress_id' => $request->ipress_id,
            ]);

            DB::commit();

            return response()->json(['message' => 'Diagnostic created successfully', 'data' => $diagnostic], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create diagnostic', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Diagnostic $diagnostic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diagnostic $diagnostic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diagnostic $diagnostic)
    {
        //
    }
}
