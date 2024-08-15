<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'posts',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'title' => $this->title,
                'message' => $this->message,
                'slug' => $this->slug,
                'created_at' => (new Carbon($this->created_at))->format('d-m-y H:i:s'),
                'updated_at' => $this->updated_at,
                'category_id' => $this->category_id, // Añadir el ID de la categoría
                'category_name' => $this->category->name ?? null, // Añadir el nombre de la categoría (si existe la relación)
            ],
            'links' => [
                'self' => route('api.posts.show', $this->resource)
            ],
            'relationships' => [
                'user' => new UserResource($this->whenLoaded('user')),
                'comment' => new CommentResource($this->whenLoaded('comment')),
                'category' => new CategoryResource($this->whenLoaded('category')), // Añadir la relación de categoría si es necesario

            ],
        ];
    }
}
