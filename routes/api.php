<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function () {
        return auth()->user();
    });

    // Temporary route for testing middleware
    Route::middleware('librarian')->get('/admin-test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Welcome Librarian!',
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Authors
    |--------------------------------------------------------------------------
    */

    // Accessible by all authenticated users
    Route::get('/authors', [AuthorController::class, 'index']);
    Route::get('/authors/{author}', [AuthorController::class, 'show']);

    // Librarian only
    Route::middleware('librarian')->group(function () {
        Route::post('/authors', [AuthorController::class, 'store']);
        Route::put('/authors/{author}', [AuthorController::class, 'update']);
        Route::delete('/authors/{author}', [AuthorController::class, 'destroy']);
    });

});