<?php

namespace App\Exports;

use App\Models\User;
use App\Enums\UserRole;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Enums\BorrowingStatus;

class MembersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::with([
            'borrowings.payment',
        ])
        ->where('role', UserRole::MEMBER)
        ->get()
        ->map(function ($member) {

            $borrowings = $member->borrowings;

            return [
                'Member ID'         => $member->id,
                'Name'              => $member->name,
                'Email'             => $member->email,
                'Phone'             => $member->phone,
                'Status'            => $member->status->value,

                'Total Borrowings'  => $borrowings->count(),

                'Active Borrowings' => $borrowings
                    ->where('status', BorrowingStatus::BORROWED)
                    ->count(),

                'Total Paid'        => $borrowings
                    ->pluck('payment.total_amount')
                    ->filter()
                    ->sum(),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Member ID',
            'Name',
            'Email',
            'Phone',
            'Status',
            'Total Borrowings',
            'Active Borrowings',
            'Total Paid',
        ];
    }
}