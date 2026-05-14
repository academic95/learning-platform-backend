<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\MyCoursesIndexRequest;
use App\Http\Resources\MyCourseResource;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseEnrollmentController extends Controller
{
    public function index(MyCoursesIndexRequest $request): JsonResponse
    {
        $enrollments = $request->user()
            ->enrollments()
            ->with('course')
            ->latest('enrolled_at')
            ->paginate($request->perPage());

        return response()->json([
            'data' => MyCourseResource::collection($enrollments->getCollection()),
            'meta' => [
                'current_page' => $enrollments->currentPage(),
                'per_page' => $enrollments->perPage(),
                'total' => $enrollments->total(),
                'last_page' => $enrollments->lastPage(),
                'from' => $enrollments->firstItem(),
                'to' => $enrollments->lastItem(),
            ],
            'links' => [
                'first' => $enrollments->url(1),
                'last' => $enrollments->url($enrollments->lastPage()),
                'prev' => $enrollments->previousPageUrl(),
                'next' => $enrollments->nextPageUrl(),
            ],
        ]);
    }

    public function store(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();

        $enrollment = CourseEnrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'progress' => 0,
            ],
        );

        if (! $enrollment->wasRecentlyCreated) {
            return response()->json([
                'message' => 'Ви вже записані на цей курс',
            ], 409);
        }

        return response()->json([
            'message' => 'Ви успішно записались на курс',
            'course' => MyCourseResource::make($enrollment->load('course')),
        ], 201);

    }
}
