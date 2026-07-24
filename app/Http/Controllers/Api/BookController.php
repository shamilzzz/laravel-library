<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $query = Book::query()
            ->with(['category', 'authors'])
            ->withCount('copies');

        $this->applySearch($query, $request, [
            'title',
            'isbn',
        ]);

        if ($request->filled('page')) {

            $books = $query
                ->latest()
                ->paginate($request->integer('per_page', 10));

            return $this->successPagination(
                $books,
                BookResource::class,
                'Books retrieved successfully.'
            );
        }

        $books = $query
            ->latest()
            ->get();

        return $this->success(
            BookResource::collection($books),
            'Books retrieved successfully.'
        );
    }

    /**
     * Store a newly created book.
     */
    public function store(StoreBookRequest $request)
    {
        $book = DB::transaction(function () use ($request) {

            $data = $request->validated();

            $authors = $data['authors'];
            unset($data['authors']);

            $book = Book::create($data);

            $book->authors()->attach($authors);

            return $book;
        });

        $book->load([
            'category',
            'authors',
        ])->loadCount('copies');

        return $this->success(
            new BookResource($book),
            'Book created successfully.',
            201
        );
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        $book->load([
            'category',
            'authors',
        ])->loadCount('copies');

        return $this->success(
            new BookResource($book),
            'Book retrieved successfully.'
        );
    }

    /**
     * Update the specified book.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book = DB::transaction(function () use ($request, $book) {

            $data = $request->validated();

            $authors = $data['authors'];
            unset($data['authors']);

            $book->update($data);

            $book->authors()->sync($authors);

            return $book;
        });

        $book->load([
            'category',
            'authors',
        ])->loadCount('copies');

        return $this->success(
            new BookResource($book),
            'Book updated successfully.'
        );
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return $this->success(
            null,
            'Book deleted successfully.'
        );
    }
}