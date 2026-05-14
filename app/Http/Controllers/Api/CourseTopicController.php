<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseTopicResource;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseTopic;
use App\Models\TopicProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseTopicController extends Controller
{
    public function index(Request $request, Course $course): AnonymousResourceCollection
    {
        $user = $request->user();
        $topics = $course->topics()
            ->with([
                'progress' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ])
            ->orderBy('position')
            ->get();

        return CourseTopicResource::collection($topics);
    }

    public function complete(Request $request, Course $course, CourseTopic $topic): JsonResponse
    {
        $user = $request->user();

        $enrollment = CourseEnrollment::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrollment) {
            return response()->json([
                'message' => 'Ви не записані на цей курс',
            ], 403);
        }

        $topicProgress = TopicProgress::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'course_topic_id' => $topic->id,
        ]);

        $totalTopics = $course->topics()->count();
        $completedTopics = TopicProgress::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->count();

        $progress = $totalTopics === 0
            ? 0
            : (int) floor(($completedTopics / $totalTopics) * 100);
        $enrollment->update([
            'progress' => $progress,
            'completed_at' => $progress === 100 ? now() : null,
        ]);

        return response()->json([
            'message' => 'Тему успішно завершено',
            'progress' => $progress,
            'completed_at' => $topicProgress->completed_at,
        ]);
    }
}
