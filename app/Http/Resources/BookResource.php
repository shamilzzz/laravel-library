<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'isbn' => $this->isbn,

            'publication_year' => $this->publication_year,

            'description' => $this->description,

            'category' => new CategoryResource($this->whenLoaded('category')),

            'authors' => AuthorResource::collection($this->whenLoaded('authors')),

            'copies_count' => $this->whenCounted('copies'),

            'price' => $this->price,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}