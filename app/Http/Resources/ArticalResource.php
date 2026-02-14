<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'author' => $this->author,
            'source' => $this->source,
            'category' => $this->category,
            'url' => $this->url,
            'image_url' => $this->image_url,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'api_version' => '1.0',
                'timestamp' => now()->toDateTimeString(),
            ],
        ];
    }
}
