<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'duration_hours' => $this->duration_hours,
            'is_mandatory' => $this->is_mandatory,
            'is_enrolled' => (bool) ($this->is_enrolled ?? false),
            'topics_count' => $this->whenCounted('topics'),
        ];
    }
}
