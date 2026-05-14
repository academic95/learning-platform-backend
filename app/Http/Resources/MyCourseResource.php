<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->course->id,
            'title' => $this->course->title,
            'is_mandatory' => $this->course->is_mandatory,
            'enrolled_at' => $this->enrolled_at,
            'progress' => $this->progress,
            'completed_at' => $this->completed_at,
        ];
    }
}
