<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseIndexRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CourseController extends Controller
{
    public function index(CourseIndexRequest $request): JsonResponse
    {
        // Стврорюємо унікальний ключ для кешу на основі параметрів запиту
        $cacheKey = sprintf(
            'courses:list:v2:page:%s:per_page:%s',
            $request->validated('page', 1),
            $request->perPage(),
        );

        // Використовуємо кеш для збереження результату
        $response = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request) {
            $courses = Course::query()
                ->withCount('topics')
                ->latest()
                ->paginate($request->perPage());

            return [
                // Використовуємо resolve() для отримання масиву даних з ресурсів
                'data' => CourseResource::collection($courses->getCollection())->resolve($request),
                'meta' => [
                    'current_page' => $courses->currentPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'last_page' => $courses->lastPage(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                ],
                'links' => [
                    'first' => $courses->url(1),
                    'last' => $courses->url($courses->lastPage()),
                    'prev' => $courses->previousPageUrl(),
                    'next' => $courses->nextPageUrl(),
                ],
            ];
        });

        return response()->json($response);
    }
}
