<?php

namespace App\Filament\Resources\CourseTopics;

use App\Filament\Resources\CourseTopics\Pages\CreateCourseTopic;
use App\Filament\Resources\CourseTopics\Pages\EditCourseTopic;
use App\Filament\Resources\CourseTopics\Pages\ListCourseTopics;
use App\Filament\Resources\CourseTopics\Schemas\CourseTopicForm;
use App\Filament\Resources\CourseTopics\Tables\CourseTopicsTable;
use App\Models\CourseTopic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CourseTopicResource extends Resource
{
    protected static ?string $model = CourseTopic::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CourseTopicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseTopicsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourseTopics::route('/'),
            'create' => CreateCourseTopic::route('/create'),
            'edit' => EditCourseTopic::route('/{record}/edit'),
        ];
    }
}
