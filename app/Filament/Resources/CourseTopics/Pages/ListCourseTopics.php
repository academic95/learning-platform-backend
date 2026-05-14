<?php

namespace App\Filament\Resources\CourseTopics\Pages;

use App\Filament\Resources\CourseTopics\CourseTopicResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourseTopics extends ListRecords
{
    protected static string $resource = CourseTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
