<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'comments',
            'id' => (string) $this->id,
            'attributes' => [
                'body' => $this->body,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'links' => [
                'self' => route('api.comments.show', $this->id),
            ],
        ];
    }
}
