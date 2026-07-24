<?php

namespace App\Http\Controllers\Api;

use App\Exports\BorrowingsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BooksExport;
use App\Exports\MembersExport;

class ReportController extends Controller
{
    public function borrowings()
    {
        return Excel::download(
            new BorrowingsExport(),
            'borrowings_report.xlsx'
        );
    }

    public function books()
    {
        return Excel::download(
            new BooksExport(),
            'books_report.xlsx'
        );
    }

    public function members()
    {
        return Excel::download(
            new MembersExport(),
            'members_report.xlsx'
        );
    }
}