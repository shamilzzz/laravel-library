<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'borrowing_id',
        'borrow_charge',
        'late_fee',
        'damage_fee',
        'lost_book_charge',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'borrow_charge' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'damage_fee' => 'decimal:2',
            'lost_book_charge' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }
}