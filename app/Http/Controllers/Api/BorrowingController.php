<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use Illuminate\Http\Request;
use App\Http\Requests\ReturnBookRequest;

class BorrowingController extends Controller
{
    public function __construct(
        private BorrowingService $borrowingService
    ) {}

    /**
     * Display a listing of borrowings.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with([
            'user',
            'bookCopy.book',
            'payment',
        ]);

        $this->applySearch(
            $query,
            $request,
            ['status']
        );

        $borrowings = $query
            ->latest()
            ->paginate($request->integer('page_size', 10));

        return $this->successPagination(
            $borrowings,
            BorrowingResource::class,
            'Borrowings retrieved successfully.'
        );
    }

    /**
     * Store a newly created borrowing.
     */
    public function store(StoreBorrowingRequest $request)
    {
        try {

            $result = $this->borrowingService->borrow(
                $request->validated()
            );

            if ($result['queued']) {
                return $this->success(
                    null,
                    $result['message'],
                    200
                );
            }

            return $this->success(
                new BorrowingResource($result['borrowing']),
                'Book borrowed successfully.',
                201
            );

        } catch (\Throwable $e) {

            return $this->error(
                $e->getMessage(),
                400
            );
        }
    }

    /**
     * Display the specified borrowing.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load([
            'user',
            'bookCopy.book',
            'payment',
        ]);

        return $this->success(
            new BorrowingResource($borrowing),
            'Borrowing retrieved successfully.'
        );
    }

    /**
     * Return a borrowed book.
     */
    public function return(Borrowing $borrowing, ReturnBookRequest $request)
    {
        try {

            $borrowing = $this->borrowingService->returnBook(
                $borrowing,
                $request->validated()
            );

            return $this->success(
                new BorrowingResource($borrowing),
                'Book returned successfully.'
            );

        } catch (\Throwable $e) {

            return $this->error(
                $e->getMessage(),
                400
            );
        }
    }



    /**
     * Mark a borrowed book as lost.
     */
    public function markAsLost(Borrowing $borrowing)
    {
        try {

            $borrowing = $this->borrowingService
                ->markAsLost($borrowing);

            return $this->success(
                new BorrowingResource($borrowing),
                'Book marked as lost.'
            );

        } catch (\Throwable $e) {

            return $this->error(
                $e->getMessage(),
                400
            );
        }
    }
}