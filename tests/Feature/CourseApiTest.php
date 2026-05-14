<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_courses_index_is_paginated(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $courses = Course::factory()->count(3)->create();
        CourseTopic::factory()->count(2)->create(['course_id' => $courses->first()->id]);
        CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $courses->first()->id,
            'progress' => 0,
        ]);

        $response = $this->getJson('/api/courses?per_page=2');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'duration_hours', 'is_mandatory', 'is_enrolled', 'topics_count'],
                ],
                'meta' => ['current_page', 'per_page', 'total', 'last_page', 'from', 'to'],
                'links' => ['first', 'last', 'prev', 'next'],
            ]);

    }

    public function test_courses_index_shows_enrollment_status_for_current_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $enrolledCourse = Course::factory()->create();
        $notEnrolledCourse = Course::factory()->create();

        CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $enrolledCourse->id,
            'progress' => 0,
        ]);

        $response = $this->getJson('/api/courses?per_page=10');

        $courses = collect($response->json('data'))->keyBy('id');

        $this->assertTrue($courses[$enrolledCourse->id]['is_enrolled']);
        $this->assertFalse($courses[$notEnrolledCourse->id]['is_enrolled']);
    }

    public function test_courses_index_cache_is_invalidated_after_course_is_created(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Course::factory()->create();

        $this->getJson('/api/courses?per_page=10')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);

        Course::factory()->create();

        $this->getJson('/api/courses?per_page=10')
            ->assertOk()
            ->assertJsonPath('meta.total', 2);
    }

    public function test_courses_index_cache_is_invalidated_after_enrollment_is_created(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/courses?per_page=10')
            ->assertOk()
            ->assertJsonPath('data.0.is_enrolled', false);

        $this->postJson("/api/courses/{$course->id}/enroll")
            ->assertCreated();

        $this->getJson('/api/courses?per_page=10')
            ->assertOk()
            ->assertJsonPath('data.0.is_enrolled', true);
    }

    public function test_courses_index_validates_pagination(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/courses?per_page=51&page=0');

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['per_page', 'page']);
    }

    public function test_user_can_enroll_in_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/courses/{$course->id}/enroll");

        $response
            ->assertCreated()
            ->assertJsonPath('message', 'Ви успішно записались на курс')
            ->assertJsonPath('course.id', $course->id);

        $this->assertDatabaseHas('course_enrollments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0,
        ]);
    }

    public function test_user_cannot_enroll_twice_in_same_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0,
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/courses/{$course->id}/enroll");

        $response
            ->assertStatus(409)
            ->assertJsonPath('message', 'Ви вже записані на цей курс');
    }

    public function test_my_courses_index_is_paginated(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $courses = Course::factory()->count(3)->create();
        foreach ($courses as $course) {
            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'progress' => 0,
            ]);
        }

        $response = $this->getJson('/api/users/me/courses?per_page=2');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'is_mandatory', 'enrolled_at', 'progress', 'completed_at'],
                ],
                'meta' => ['current_page', 'per_page', 'total', 'last_page', 'from', 'to'],
                'links' => ['first', 'last', 'prev', 'next'],
            ]);
    }
}
