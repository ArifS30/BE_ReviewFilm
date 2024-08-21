<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CastMovieRequest;
use App\Models\Cast_Movie;



class CastMovieController extends Controller
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
        $Cast_Movie = Cast_Movie::all();

        return response()->json([
            'message' => 'tampil semua Cast',
            'data' => $Cast_Movie,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CastMovieRequest $request)
    {
        
        Cast_Movie::create($request->all());

        return response()->json(['message' => 'Cast Movie berhasil ditambahkan'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Cast_Movie = Cast_Movie::with('movie', 'cast')->find($id);
        
        if(!$Cast_Movie){
            return response()->json([
                'message' => 'Cast Movie tidak ditemukan!'
            ],404);
        }

        return response()->json([
            'message' => 'Cast Movie dengan id :'.$id,
            'data' => $Cast_Movie
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Cast_Movie $request, string $id)
    {
        $Cast_Movie = Cast_Movie::find($id);

        if(!$Cast_Movie){
            return response()->json([
                'message' => 'Cast Movie tidak ditemukan!'
            ],404);
        }
 
        //    $Cast_Movie->name = $request->name;
        //    $Cast_Movie->cast_id = $request->cast_id;
        //    $Cast_Movie->movie_id = $request->movie_id;

         $Cast_Movie->save();
         return response()->json([
            'message' => 'Data Cast Movie berhasil diperbaharui id:'.$id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Cast_Movie = Cast_Movie::find($id);
        
        if(!$Cast_Movie){
            return response()->json([
                'message' => 'Cast Movie tidak ditemukan!'
            ],404);
        }

        $Cast_Movie->delete();

        return response()->json([
            'message' => 'Data Cast Movie berhasil dihapus id:'.$id], 201);
    }
}
