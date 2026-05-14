<?php

namespace App\Filament\Resources\CourseTopics\Pages;

use App\Filament\Resources\CourseTopics\CourseTopicResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourseTopic extends EditRecord
{
    protected static string $resource = CourseTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
