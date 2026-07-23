<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    */

    Route::middleware('librarian')->get('/admin-test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Welcome Librarian!',
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Authors (Authenticated Users)
    |--------------------------------------------------------------------------
    */

    Route::get('/authors', [AuthorController::class, 'index']);
    Route::get('/authors/{author}', [AuthorController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Categories (Authenticated Users)
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Librarian Only Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('librarian')->group(function () {

        // Authors
        Route::post('/authors', [AuthorController::class, 'store']);
        Route::put('/authors/{author}', [AuthorController::class, 'update']);
        Route::delete('/authors/{author}', [AuthorController::class, 'destroy']);

        // Categories
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    });
});