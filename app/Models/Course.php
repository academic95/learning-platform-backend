<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable([
    'title',
    'description',
    'duration_hours',
    'is_mandatory',
])]
class Course extends Model
{
    use HasFactory;

    // Ключ для зберігання версії кешу списку курсів, щоб ефективно інвалідовувати кеш при змінах
    public const LIST_CACHE_VERSION_KEY = 'courses:list:version';

    protected static function booted(): void
    {
        static::saved(fn () => self::bumpListCacheVersion());
        static::deleted(fn () => self::bumpListCacheVersion());
    }

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

    public static function listCacheVersion(): int
    {
        return (int) Cache::get(self::LIST_CACHE_VERSION_KEY, 1);
    }

    public static function bumpListCacheVersion(): int
    {
        Cache::add(self::LIST_CACHE_VERSION_KEY, 1);

        return Cache::increment(self::LIST_CACHE_VERSION_KEY);
    }
}
