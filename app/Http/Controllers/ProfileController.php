<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required',
            'biodata' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $profile = Profile::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $request->all()
        );
        return response()->json([
            'message' => 'profile berhasil diubah!',
            'data' => $profile
        ]);
    }
}
