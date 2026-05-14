<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseTopic;
use App\Models\TopicProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseTopicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_course_topics_with_progress(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $topic = CourseTopic::factory()->create([
            'course_id' => $course->id,
            'position' => 1,
        ]);
        TopicProgress::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'course_topic_id' => $topic->id,
        ]);
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/courses/{$course->id}/topics");

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $topic->id)
            ->assertJsonPath('data.0.is_completed', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'position', 'is_completed', 'completed_at'],
                ],
            ]);
    }

    public function test_user_must_be_enrolled_to_complete_topic(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $topic = CourseTopic::factory()->create(['course_id' => $course->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/courses/{$course->id}/topics/{$topic->id}/complete");

        $response
            ->assertForbidden()
            ->assertJsonPath('message', 'Ви не записані на цей курс');
    }

    public function test_user_can_complete_topic_and_progress_is_recalculated(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $firstTopic = CourseTopic::factory()->create([
            'course_id' => $course->id,
            'position' => 1,
        ]);
        CourseTopic::factory()->create([
            'course_id' => $course->id,
            'position' => 2,
        ]);
        $enrollment = CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0,
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/courses/{$course->id}/topics/{$firstTopic->id}/complete");

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Тему успішно завершено')
            ->assertJsonPath('progress', 50)
            ->assertJsonStructure(['message', 'progress', 'completed_at']);

        $this->assertDatabaseHas('topic_progress', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'course_topic_id' => $firstTopic->id,
        ]);
        $this->assertSame(50, $enrollment->refresh()->progress);
        $this->assertNull($enrollment->completed_at);
    }

    public function test_topic_must_belong_to_course_when_completing(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $otherCourse = Course::factory()->create();
        $topic = CourseTopic::factory()->create(['course_id' => $otherCourse->id]);
        CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0,
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/courses/{$course->id}/topics/{$topic->id}/complete");

        $response->assertNotFound();
    }
}
