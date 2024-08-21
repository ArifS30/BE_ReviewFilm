<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\movie;
use App\Http\Requests\MovieRequest;
use Illuminate\Support\Facades\Storage;

class moviescontroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only('store', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movieTrending = movie::select('id', 'title', 'poster')->inRandomOrder()->limit(5)->get();
        $movie = movie::select('movie.id', 'movie.title', 'movie.poster', 'movie.summary', 'genres.name as genre')->join('genres', 'genres.id', '=', 'movie.genre_id')->get();

        return response()->json([
            'message' => 'Get Movie List Success',
            'trending' => $movieTrending,
            'data' => $movie,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieRequest $request)
    {
        $data = $request->validated();

        // jika file gambar diinput

        if ($request->hasFile('poster')) {

            // membuat unique name pada gamabr yang di input

            $posterName = time() . '.' . $request->poster->extension();

            // simpan gambar pada file storage

            $request->poster->storeAs('public/poster', $posterName);

            // menganti request nilai request image menjadi $imageName yang baru bukan berdasarkan request

            $path = env('APP_URL') . '/storage/poster/';

            $data['poster'] = $path . $posterName;

        }

        movie::create($data);

        return response()->json([
            'message' => 'movie berhasil ditambahkan!'
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movie = movie::with(['list_cast', 'list_reviews'])->find($id);

        if (!$movie) {
            return response()->json([
                'message' => 'Movie id tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'message' => 'Movie dengan id :' . $id,
            'data' => $movie
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieRequest $request, string $id)
    {
        $data = $request->validated();

        $movieData = movie::find($id);

        if (!$movieData) {
            return response()->json([
                'message' => 'Movie id tidak ditemukan!'
            ], 404);
        }

        if ($request->hasFile('poster')) {

            // Hapus gambar lama jika ada

            if ($movieData->poster) {

                $posterName = basename($movieData->poster);
                Storage::delete('public/poster/' . $posterName);
            }
            //untuk uniqe nama poster baru
            $posterName = time() . '.' . $request->poster->extension();

            $request->poster->storeAs('public/poster', $posterName);

            $path = env('APP_URL') . '/storage/poster/';

            $data['poster'] = $path . $posterName;

        }

        $movieData->update($data);

        return response()->json(['Data movie berhasil diupdate!'], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movieData = movie::find($id);

        if (!$movieData) {
            return response()->json([
                'message' => 'Movie id tidak ditemukan!'
            ], 404);
        }

        if ($movieData->poster) {

            $posterName = basename($movieData->poster);
            Storage::delete('public/poster/' . $posterName);
        }

        $movieData->delete();

        return response()->json(['Data movie berhasil dihapus!'], 200);
    }
}
