<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyCourseResource;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseEnrollmentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $enrollments = $request->user()
            ->courseEnrollments()
            ->with('course')
            ->latest('enrolled_at')
            ->get();

        return MyCourseResource::collection($enrollments);
    }

    public function store(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();
        $exists = CourseEnrollment::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Already enrolled in this course',
            ], 409);
        }

        CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0,
        ]);

        return response()->json([
            'message' => 'Successfully enrolled',
        ]);

    }
}
