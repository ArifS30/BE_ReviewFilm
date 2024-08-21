<?php

namespace App\Http\Controllers;

use App\Mail\GenerateOTPMail;
use App\Models\Roles;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\otpCode;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $roleUser = Roles::where('name', 'user')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleUser->id
        ]);

        $user->generateotpCode();

        $token = JWTAuth::fromUser($user);

        Mail::to($user->email)->Queue(new RegisterMail($user));

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function generateotpCode(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        $userData = User::where('email', $request->email)->first();

        $userData->generateotpCode();

        Mail::to($userData->email)->Queue(new GenerateOTPMail($userData));

        return response()->json([
            'message' => 'Berhasil generate ulang otp code',
            'data' => $userData,
        ], 201);
    }

    public function verifikasi(Request $request){
        $request->validate([
            'otp' => 'required',
        ]);

        $otpCode = otpCode::where('otp', $request->otp)->first();

        if(!$otpCode){
            return response()->json([
                'message' => 'Otp code tidak ditemukan!'
            ],404);
        }

        $now = Carbon::now();

        if($now > $otpCode->valid_until){
            return response()->json([
                'message' => 'Otp code kadaluarsa silahkan generate ulang!'
            ],404);
        }

        $user = User::find($otpCode->user_id);
        $user->email_verified_at = $now;

        $user->save();

        $otpCode->delete();
        return response()->json([
            'message' => 'Berhasil verifikasi akun',
        ]);
    }

    public function getUser()
    {
        $currentUser = auth()->user();

        return response()->json([
            'message' => 'Berhasil',
            'user' => $currentUser
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Login Gagal'
            ], 401);
        }

        $userData = User::with('Role')->where('email', $request['email'])->first();
        $token = JWTAuth::fromUser($userData);
        return response()->json([
            'user' => $userData,
            'token' => $token
        ], 201);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Logout Berhasil'
        ]);
    }
    public function update(Request $request)
    {
        if ($request->bearerToken() == null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = JWTAuth::parseToken()->authenticate();

        $user->name = $request->get('name');
        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
}
