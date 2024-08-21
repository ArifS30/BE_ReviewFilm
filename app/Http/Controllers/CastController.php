<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cast;
use App\Http\Requests\CastsRequest;

class CastController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api','isAdmin'])->only('store','update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Casts = Cast::all();

        return response()->json([
            'message' => 'tampil semua Cast',
            'data' => $Casts,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CastsRequest $request)
    {
        Cast::create($request->all());

        return response()->json(['message' => 'Data Cast berhasil ditambahkan'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Cast = Cast::with('list_movies')->find($id);
        
        if(!$Cast){
            return response()->json([
                'message' => 'Cast tidak ditemukan!'
            ],404);
        }

        return response()->json([
            'message' => 'Cast dengan id :'.$id,
            'data' => $Cast
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CastsRequest $request, string $id)
    {
        $Cast = Cast::find($id);

        if(!$Cast){
            return response()->json([
                'message' => 'Cast tidak ditemukan!'
            ],404);
        }
 
        $Cast->name = $request->name;
        $Cast->bio = $request->bio;
        $Cast->age = $request->age;
 
         $Cast->save();
         return response()->json([
            'message' => 'Data Cast berhasil diperbaharui id:'.$id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Cast = Cast::find($id);
        
        if(!$Cast){
            return response()->json([
                'message' => 'Cast tidak ditemukan!'
            ],404);
        }

        $Cast->delete();

        return response()->json([
            'message' => 'Data Cast berhasil dihapus id:'.$id], 201);
    }
}
