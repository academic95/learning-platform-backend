<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseEnrollmentController extends Controller
{
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
