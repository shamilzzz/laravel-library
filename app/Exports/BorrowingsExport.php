<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BorrowingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Borrowing::with([
            'user',
            'bookCopy.book',
            'payment',
        ])
        ->latest()
        ->get()
        ->map(function ($borrowing) {
            return [
                'Borrowing ID'      => $borrowing->id,
                'Member'            => $borrowing->user->name,
                'Book'              => $borrowing->bookCopy->book->title,
                'Accession Number'  => $borrowing->bookCopy->accession_number,
                'Borrowed At'       => $borrowing->borrowed_at,
                'Due Date'          => $borrowing->due_date,
                'Returned At'       => $borrowing->returned_at,
                'Status'            => $borrowing->status->value,
                'Borrow Charge'     => $borrowing->payment?->borrow_charge ?? 0,
                'Late Fee'          => $borrowing->payment?->late_fee ?? 0,
                'Damage Fee'        => $borrowing->payment?->damage_fee ?? 0,
                'Lost Book Charge'  => $borrowing->payment?->lost_book_charge ?? 0,
                'Total Amount'      => $borrowing->payment?->total_amount ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Borrowing ID',
            'Member',
            'Book',
            'Accession Number',
            'Borrowed At',
            'Due Date',
            'Returned At',
            'Status',
            'Borrow Charge',
            'Late Fee',
            'Damage Fee',
            'Lost Book Charge',
            'Total Amount',
        ];
    }
}