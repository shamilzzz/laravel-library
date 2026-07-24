<?php

namespace App\Services;

use App\Models\BookCopy;

class AccessionNumberService
{
    /**
     * Generate multiple accession numbers.
     */
    public function generateMany(int $quantity): array
    {
        $lastCopy = BookCopy::latest('id')->first();

        $lastNumber = $lastCopy
            ? (int) substr($lastCopy->accession_number, 3)
            : 0;

        $numbers = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $numbers[] = 'ACC' . str_pad(
                $lastNumber + $i,
                6,
                '0',
                STR_PAD_LEFT
            );
        }

        return $numbers;
    }
}