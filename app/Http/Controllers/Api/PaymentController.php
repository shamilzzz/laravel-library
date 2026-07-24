<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with([
            'borrowing.user',
            'borrowing.bookCopy.book',
        ]);

        $payments = $query
            ->latest()
            ->paginate($request->integer('page_size', 10));

        return $this->successPagination(
            $payments,
            PaymentResource::class,
            'Payments retrieved successfully.'
        );
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load([
            'borrowing.user',
            'borrowing.bookCopy.book',
        ]);

        return $this->success(
            new PaymentResource($payment),
            'Payment retrieved successfully.'
        );
    }
}