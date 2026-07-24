<?php

namespace App\Services;

use App\Enums\BookCopyStatus;
use App\Enums\BorrowingStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\BookCopy;
use App\Models\BookQueue;
use App\Models\Borrowing;
use App\Models\LibrarySetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BorrowingService
{
    public function borrow(array $data): array
    {
        // 1. Find member
        $member = User::findOrFail($data['member_id']);

        // 2. Validate member
        if ($member->role !== UserRole::MEMBER) {
            throw new \Exception('Selected user is not a member.');
        }

        if ($member->status !== UserStatus::ACTIVE) {
            throw new \Exception('Member account is not active.');
        }

        // 3. Load library settings
        $settings = LibrarySetting::first();

        if (!$settings) {
            throw new \Exception('Library settings not found.');
        }

        // 4. Validate borrow days
        if ($data['borrow_days'] > $settings->max_borrow_days) {
            throw new \Exception(
                "Maximum borrowing period is {$settings->max_borrow_days} days."
            );
        }

        // 5. Check borrowing limit
        $activeBorrowings = Borrowing::where('user_id', $member->id)
            ->where('status', BorrowingStatus::BORROWED)
            ->count();

        if ($activeBorrowings >= $settings->max_borrow_limit) {
            throw new \Exception('Borrowing limit reached.');
        }

        // 6. Find the oldest available copy
        $copy = BookCopy::where('book_id', $data['book_id'])
            ->where('status', BookCopyStatus::AVAILABLE)
            ->orderBy('id')
            ->first();

        // 7. No copy available? Add member to queue
        if (!$copy) {

            // Prevent duplicate queue entries
            $alreadyQueued = BookQueue::where('user_id', $member->id)
                ->where('book_id', $data['book_id'])
                ->exists();

            if ($alreadyQueued) {
                throw new \Exception('Member is already in the waiting queue for this book.');
            }

            $position = BookQueue::where('book_id', $data['book_id'])
                ->max('position');

            BookQueue::create([
                'user_id' => $member->id,
                'book_id' => $data['book_id'],
                'position' => ($position ?? 0) + 1,
            ]);

            return [
                'queued' => true,
                'message' => 'No copies available. Member added to waiting queue.',
            ];
        }

        // 8. Borrow the book
        return DB::transaction(function () use ($member, $copy, $data) {

            $borrowing = Borrowing::create([
                'user_id' => $member->id,
                'book_copy_id' => $copy->id,
                'borrowed_at' => now(),
                'due_date' => now()->addDays($data['borrow_days']),
                'status' => BorrowingStatus::BORROWED,
            ]);

            $copy->update([
                'status' => BookCopyStatus::BORROWED,
            ]);

            return [
                'queued' => false,
                'borrowing' => $borrowing->load([
                    'user',
                    'bookCopy.book',
                ]),
            ];
        });
    }



    public function returnBook(Borrowing $borrowing, array $data = []): Borrowing
    {
        if ($borrowing->status !== BorrowingStatus::BORROWED) {
            throw new \Exception('This borrowing has already been returned.');
        }

        $settings = LibrarySetting::firstOrFail();

        return DB::transaction(function () use ($borrowing, $settings, $data) {

            $returnedAt = now();

            $lateDays = max(
                0,
                $returnedAt->startOfDay()->diffInDays(
                    $borrowing->due_date->copy()->startOfDay(),
                    false
                ) * -1
            );

            $borrowCharge = $settings->borrow_charge;
            $lateFee = $lateDays * $settings->late_fee_per_day;
            $damageFee = $data['damage_fee'] ?? 0;

            $borrowing->update([
                'returned_at' => $returnedAt,
                'status' => BorrowingStatus::RETURNED,
            ]);

            $borrowing->bookCopy()->update([
                'status' => BookCopyStatus::AVAILABLE,
            ]);

            $borrowing->payment()->create([
                'borrow_charge' => $borrowCharge,
                'late_fee' => $lateFee,
                'damage_fee' => $damageFee,
                'lost_book_charge' => 0,
                'total_amount' => $borrowCharge + $lateFee + $damageFee,
            ]);
            
            $this->processQueue($borrowing->bookCopy->book_id);


            return $borrowing->load([
                'user',
                'bookCopy.book',
                'payment',
            ]);
        });
    }

    private function processQueue(int $bookId): void
    {
        $queue = BookQueue::where('book_id', $bookId)
            ->whereNull('notified_at')
            ->orderBy('position')
            ->first();

        if (!$queue) {
            return;
        }

        $queue->update([
            'notified_at' => now(),
        ]);
    }


    public function markAsLost(Borrowing $borrowing): Borrowing
    {
        if ($borrowing->status !== BorrowingStatus::BORROWED) {
            throw new \Exception('This borrowing is already closed.');
        }

        $settings = LibrarySetting::firstOrFail();

        return DB::transaction(function () use ($borrowing, $settings) {

            $today = now();

            $lateDays = max(
                0,
                $today->startOfDay()->diffInDays(
                    $borrowing->due_date->copy()->startOfDay(),
                    false
                ) * -1
            );

            $borrowCharge = $settings->borrow_charge;
            $lateFee = $lateDays * $settings->late_fee_per_day;
            $lostBookCharge = $borrowing->bookCopy->book->price;

            $borrowing->update([
                'returned_at' => $today,
                'status' => BorrowingStatus::LOST,
            ]);

            $borrowing->bookCopy()->update([
                'status' => BookCopyStatus::LOST,
            ]);

            $borrowing->payment()->create([
                'borrow_charge' => $borrowCharge,
                'late_fee' => $lateFee,
                'damage_fee' => 0,
                'lost_book_charge' => $lostBookCharge,
                'total_amount' => $borrowCharge + $lateFee + $lostBookCharge,
            ]);

            return $borrowing->load([
                'user',
                'bookCopy.book',
                'payment',
            ]);
        });
    }
}