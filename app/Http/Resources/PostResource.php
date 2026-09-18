<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'content' => $this->content,
            'is_active' => $this->deleted_at === null,
            'created_at' => $this->created_at?->diffForHumans(),
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author?->first_name.' '.$this->author?->last_name,
                'attachment' => $this->author?->attachment,
            ],
        ];
    }
}
