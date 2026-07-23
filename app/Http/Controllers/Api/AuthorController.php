<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of authors.
     */
    public function index(Request $request)
    {
        $query = Author::query();

        $query = $this->applySearch(
            $query,
            $request,
            ['name']
        );

        $authors = $query
            ->orderBy('name')
            ->paginate($request->integer('per_page', 10));

        return $this->successPagination(
            $authors,
            AuthorResource::class,
            'Authors retrieved successfully.'
        );
    }

    /**
     * Store a newly created author.
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());

        return $this->success(
            new AuthorResource($author),
            'Author created successfully.',
            201
        );
    }

    /**
     * Display the specified author.
     */
    public function show(Author $author)
    {
        return $this->success(
            new AuthorResource($author),
            'Author retrieved successfully.'
        );
    }

    /**
     * Update the specified author.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());

        return $this->success(
            new AuthorResource($author),
            'Author updated successfully.'
        );
    }

    /**
     * Remove the specified author.
     */
    public function destroy(Author $author)
    {
        // Later we'll prevent deletion if books exist.
        $author->delete();

        return $this->success(
            null,
            'Author deleted successfully.'
        );
    }
}