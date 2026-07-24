<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookCopyController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LibrarySettingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;
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
    | Authors
    |--------------------------------------------------------------------------
    */

    Route::get('/authors', [AuthorController::class, 'index']);
    Route::get('/authors/{author}', [AuthorController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Books
    |--------------------------------------------------------------------------
    */

    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{book}', [BookController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Book Copies
    |--------------------------------------------------------------------------
    */

    Route::get('/book-copies', [BookCopyController::class, 'index']);
    Route::get('/book-copies/{bookCopy}', [BookCopyController::class, 'show']);
    Route::get('/books/{book}/copies', [BookCopyController::class, 'bookCopies']);

    /*
    |--------------------------------------------------------------------------
    | Borrowings
    |--------------------------------------------------------------------------
    */

    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Library Settings
    |--------------------------------------------------------------------------
    */

    Route::get('/library-settings', [LibrarySettingController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Librarian Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('librarian')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Authors
        |--------------------------------------------------------------------------
        */

        Route::post('/authors', [AuthorController::class, 'store']);
        Route::put('/authors/{author}', [AuthorController::class, 'update']);
        Route::delete('/authors/{author}', [AuthorController::class, 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Books
        |--------------------------------------------------------------------------
        */

        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{book}', [BookController::class, 'update']);
        Route::delete('/books/{book}', [BookController::class, 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Book Copies
        |--------------------------------------------------------------------------
        */

        Route::post('/book-copies', [BookCopyController::class, 'store']);
        Route::put('/book-copies/{bookCopy}', [BookCopyController::class, 'update']);
        Route::delete('/book-copies/{bookCopy}', [BookCopyController::class, 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Borrowings
        |--------------------------------------------------------------------------
        */

        Route::post('/borrowings', [BorrowingController::class, 'store']);
        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'return']);
        Route::post('/borrowings/{borrowing}/lost', [BorrowingController::class, 'markAsLost']);

        /*
        |--------------------------------------------------------------------------
        | Library Settings
        |--------------------------------------------------------------------------
        */

        Route::put('/library-settings', [LibrarySettingController::class, 'update']);


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::prefix('reports')->group(function () {
            Route::get('/export/borrowings', [ReportController::class, 'borrowings']);

            Route::get('/export/books',[ReportController::class, 'books']);

            Route::get('/export/members',[ReportController::class, 'members']);

        });
    });
});