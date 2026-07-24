<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookCopyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookCopy\StoreBookCopyRequest;
use App\Http\Requests\BookCopy\UpdateBookCopyRequest;
use App\Http\Resources\BookCopyResource;
use App\Models\BookCopy;
use App\Services\AccessionNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

class BookCopyController extends Controller
{
    public function __construct(
        protected AccessionNumberService $accessionNumberService
    ) {
    }

    /**
     * Display a listing of book copies.
     */
    public function index(Request $request)
    {
        $query = BookCopy::query()
            ->with('book');

        $this->applySearch($query, $request, [
            'accession_number',
        ]);

        if ($request->filled('page')) {

            $copies = $query
                ->latest()
                ->paginate($request->integer('per_page', 10));

            return $this->successPagination(
                $copies,
                BookCopyResource::class,
                'Book copies retrieved successfully.'
            );
        }

        $copies = $query
            ->latest()
            ->get();

        return $this->success(
            BookCopyResource::collection($copies),
            'Book copies retrieved successfully.'
        );
    }






    
    /**
 * Display all copies for a specific book.
 */
public function bookCopies(Book $book)
{
    $copies = $book->copies()
        ->latest()
        ->get();

    return $this->success(
        BookCopyResource::collection($copies),
        'Book copies retrieved successfully.'
    );
}



    /**
     * Store newly created book copies.
     */
    
    public function store(StoreBookCopyRequest $request)
    {
        $copies = DB::transaction(function () use ($request) {

            $accessionNumbers = $this->accessionNumberService
                ->generateMany($request->quantity);

            $createdCopies = [];

            foreach ($accessionNumbers as $number) {

                $copy = BookCopy::create([
                    'book_id' => $request->book_id,
                    'accession_number' => $number,
                    'status' => BookCopyStatus::AVAILABLE,
                ]);

                $copy->load('book');

                $createdCopies[] = $copy;
            }

            return collect($createdCopies);
        });

        return $this->success(
            BookCopyResource::collection($copies),
            'Book copies created successfully.',
            201
        );
    }

    /**
     * Display the specified book copy.
     */
    public function show(BookCopy $bookCopy)
    {
        $bookCopy->load('book');

        return $this->success(
            new BookCopyResource($bookCopy),
            'Book copy retrieved successfully.'
        );
    }

    /**
     * Update the specified book copy.
     */
    public function update(
        UpdateBookCopyRequest $request,
        BookCopy $bookCopy
    ) {
        $bookCopy->update($request->validated());

        $bookCopy->load('book');

        return $this->success(
            new BookCopyResource($bookCopy),
            'Book copy updated successfully.'
        );
    }

    /**
     * Remove the specified book copy.
     */
    public function destroy(BookCopy $bookCopy)
    {
        $bookCopy->delete();

        return $this->success(
            null,
            'Book copy deleted successfully.'
        );
    }
}