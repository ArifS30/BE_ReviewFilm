<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\moviesController;
use App\Http\Controllers\CastController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\CastMovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->group(function () {
    Route::apiResource("Genre", GenreController::class);
    Route::apiResource("Cast", CastController::class);
    Route::apiResource("movie", moviesController::class);
    Route::apiResource("Cast-Movie", CastMovieController::class);
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('generate-otp-code', [AuthController::class, 'generateotpCode'])->middleware('auth:api');
        Route::post('verifikasi-akun', [AuthController::class, 'verifikasi']);
    });
    Route::get('/me', [AuthController::class, 'getUser'])->middleware('auth:api');
    Route::post('/update', [AuthController::class, 'update'])->middleware(['auth:api','isVerificationAccount']);
    Route::get('/roles', [RolesController::class, 'index'])->middleware(['auth:api','isAdmin']);
    Route::post('/create-roles', [RolesController::class, 'register'])->middleware(['auth:api','isAdmin']);
    Route::post('/update-roles/{id}', [RolesController::class, 'update'])->middleware(['auth:api','isAdmin']);
    Route::delete('/delete-roles/{id}', [RolesController::class, 'destroy'])->middleware(['auth:api','isAdmin']);
    Route::post('/profile', [ProfileController::class, 'store'])->middleware(['auth:api','isVerificationAccount']);
    Route::post('/review', [ReviewController::class, 'store'])->middleware(['auth:api','isVerificationAccount']);
});
