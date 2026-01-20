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
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $limit = (int) $request->query('limit', 10);
        $page = (int) $request->query('page', 1);

        if ($limit < 1) {
            $limit = 10;
        }
        if ($limit > 20) {
            $limit = 20;
        }
        if ($page < 1) {
            $page = 1;
        }

        $query = Cie10::query()
            ->select('id', 'name', 'category_code', 'disease_code');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('category_code', 'like', '%' . $search . '%')
                    ->orWhere('disease_code', 'like', '%' . $search . '%');
            });
        }

        return $query
            ->orderBy('name')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();
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
