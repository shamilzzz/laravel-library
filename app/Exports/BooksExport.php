<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Book::with(['authors', 'category', 'copies'])
            ->get()
            ->map(function ($book) {

                $copies = $book->copies;

                return [
                    'Book ID'        => $book->id,
                    'Title'          => $book->title,
                    'ISBN'           => $book->isbn,
                    'Authors'        => $book->authors->pluck('name')->implode(', '),
                    'Category'       => $book->category->name,
                    'Published Year' => $book->publication_year,
                    'Price'          => $book->price,

                    'Total Copies'   => $copies->count(),
                    'Available'      => $copies->where('status', 'available')->count(),
                    'Borrowed'       => $copies->where('status', 'borrowed')->count(),
                    'Lost'           => $copies->where('status', 'lost')->count(),
                    'Damaged'        => $copies->where('status', 'damaged')->count(),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Book ID',
            'Title',
            'ISBN',
            'Author',
            'Category',
            'Published Year',
            'Price',
            'Total Copies',
            'Available',
            'Borrowed',
            'Lost',
            'Damaged',
        ];
    }
}