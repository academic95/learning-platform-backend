<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseTopic>
 */
class CourseTopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'position' => 1,
        ];
    }
}
