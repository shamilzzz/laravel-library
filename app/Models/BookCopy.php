<?php

namespace App\Models;

use App\Enums\BookCopyStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookCopy extends Model
{
    protected $fillable = [
        'book_id',
        'accession_number',
        'status',
    ];

    protected $casts = [
        'status' => BookCopyStatus::class,
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }
}