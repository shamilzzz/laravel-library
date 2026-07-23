<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait QueryHelper
{
    protected function applySearch(
        Builder $query,
        Request $request,
        array $columns
    ): Builder {

        if ($request->filled('search')) {

            $query->where(function ($query) use ($columns, $request) {

                foreach ($columns as $index => $column) {

                    if ($index === 0) {
                        $query->where($column, 'like', "%{$request->search}%");
                    } else {
                        $query->orWhere($column, 'like', "%{$request->search}%");
                    }

                }

            });

        }

        return $query;
    }
}