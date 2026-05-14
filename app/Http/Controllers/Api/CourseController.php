<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $courses = Course::query()
            ->withCount('topics')
            ->latest()
            ->get();

        return CourseResource::collection($courses);
    }
}
