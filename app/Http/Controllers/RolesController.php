<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;

use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{

    public function index()
    {
        $roles = Roles::all();
        return response()->json([
            'message' => 'tampil semua Role',
            'data' => $roles,
        ],200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $role = Roles::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'User berhasil ditambahkan',
        ], 201);
    }

   public function update(Request $request,string $id)
    {
        $role = Roles::find($id);

        if(!$role){
            return response()->json([
                'message' => 'Role tidak ditemukan!'
            ],404);
        }
        
        $role->name = $request->name;

        $role->save();
        return response()->json([
            'message' => 'Data role berhasil diubah',
            'data' => $role
        ]);
    }

    public function destroy(string $id)
    {
        $role = Roles::find($id);
        if(!$role){
            return response()->json([
                'message' => 'Role tidak ditemukan!'
            ],404);
        }
        $role->delete();
        return response()->json([
            'message' => 'Data role berhasil dihapus',
        ]);
    }
}