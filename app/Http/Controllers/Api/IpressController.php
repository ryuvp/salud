<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ipress;
use Illuminate\Http\Request;

class IpressController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json("hokl");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        return response()->json("hokl");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
        //
    }

    public function getIpressByUbigeo($ubigeo_inei)
    {
        $ipress = Ipress::where('ubigeo', $ubigeo_inei)->get();
        return response()->json($ipress);
    }
}
