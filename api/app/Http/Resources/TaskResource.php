<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->hash,
            "title" => $this->title,
            "description" => $this->description,
            "status" => $this->status,
            "completed_at" => $this->completed_at,
            "user" => UserResource::make($this->user),
            "slug" => Str::slug($this->title),
            "project_id" => $this->hash
        ];
    }
}
