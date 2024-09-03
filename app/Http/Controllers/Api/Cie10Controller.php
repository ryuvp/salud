<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cie10;
use Illuminate\Http\Request;

class Cie10Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return all cie10
        return Cie10::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cie10 $cie10)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cie10 $cie10)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cie10 $cie10)
    {
        //
    }
}
