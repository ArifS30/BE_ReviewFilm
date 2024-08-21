<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Http\Requests\GenresRequest;

class GenreController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api','isAdmin'])->only(
            'store',
        'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Genres = Genre::all();

        return response()->json([
            'message' => 'tampil semua Genre',
            'data' => $Genres,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenresRequest $request)
    {
        Genre::create($request->all());

        return response()->json(['message' => 'Data Genre berhasil ditambahkan'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Genre = Genre::with('list_movies')->find($id);

        if(!$Genre){
            return response()->json([
                'message' => 'Genre tidak ditemukan!'
            ],404);
        }

        return response()->json([
            'message' => 'Genre dengan id :'.$id,
            'data' => $Genre
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Genre = Genre::find($id);

        if(!$Genre){
            return response()->json([
                'message' => 'Genre tidak ditemukan!'
            ],404);
        }

        $Genre->name = $request->name;


         $Genre->save();
         return response()->json([
            'message' => 'Data Genre berhasil diperbaharui id:'.$id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Genre = Genre::find($id);

        if(!$Genre){
            return response()->json([
                'message' => 'Genre tidak ditemukan!'
            ],404);
        }

        $Genre->delete();

        return response()->json([
            'message' => 'Data Genre berhasil dihapus id:'.$id], 201);
    }
}
