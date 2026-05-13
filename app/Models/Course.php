<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'description',
    'duration_hours',
    'is_mandatory',
])]
class Course extends Model
{
    use HasFactory;

    public function topics(): HasMany
    {
        return $this->hasMany(CourseTopic::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function topicProgress(): HasMany
    {
        return $this->hasMany(TopicProgress::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments')
            ->withPivot(['progress', 'enrolled_at', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'duration_hours' => 'integer',
            'is_mandatory' => 'boolean',
        ];
    }
}
