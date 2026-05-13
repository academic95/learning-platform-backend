<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Фінансовий моніторинг',
                'description' => 'Основи AML та KYC процедур',
                'duration_hours' => 4,
                'is_mandatory' => true,
                'topics' => [
                    ['title' => 'Вступ до AML', 'description' => 'Що таке AML та навіщо він потрібен'],
                    ['title' => 'KYC процедури', 'description' => 'Ідентифікація та верифікація клієнтів'],
                    ['title' => 'Підозрілі операції', 'description' => 'Як виявляти ризикові транзакції'],
                ],
            ],
            [
                'title' => 'Кібербезпека для співробітників',
                'description' => 'Базові правила захисту даних та облікових записів',
                'duration_hours' => 3,
                'is_mandatory' => true,
                'topics' => [
                    ['title' => 'Паролі та MFA', 'description' => 'Як безпечно працювати з доступами'],
                    ['title' => 'Фішинг', 'description' => 'Як розпізнавати підозрілі листи'],
                    ['title' => 'Безпечна робота з файлами', 'description' => 'Основи цифрової гігієни'],
                ],
            ],
            [
                'title' => 'Клієнтський сервіс',
                'description' => 'Комунікація з клієнтами банку',
                'duration_hours' => 2,
                'is_mandatory' => false,
                'topics' => [
                    ['title' => 'Стандарти комунікації', 'description' => 'Тон, структура та якість відповіді'],
                    ['title' => 'Складні клієнти', 'description' => 'Як працювати з конфліктними ситуаціями'],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $topics = $courseData['topics'];
            unset($courseData['topics']);

            $course = Course::factory()->create($courseData);

            foreach ($topics as $index => $topic) {
                $course->topics()->create([
                    'title' => $topic['title'],
                    'description' => $topic['description'],
                    'position' => $index + 1,
                ]);
            }
        }
    }
}
