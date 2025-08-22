<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class ProjectResource extends JsonResource
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
            "project_name" => $this->project_name,
            "user" => UserResource::make($this->user),
            "slug" => Str::slug($this->project_name)
        ];
    }
}
