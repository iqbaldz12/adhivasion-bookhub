<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'author'         => $this->author,
            'published_year' => $this->published_year,
            'isbn'           => $this->isbn,
            'stock'          => $this->stock,
            'created_at'     => $this->created_at,
        ];
    }
}
