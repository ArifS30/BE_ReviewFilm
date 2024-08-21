<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'critics' => 'required',
            'rating' => 'required|between:1,5',
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movie,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); 
        }
        $Review = Review::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $request->all()
        );
        return response()->json([
            'message' => 'Review berhasil ditambahkan',
            'data' => $Review
        ]);
    }
}
