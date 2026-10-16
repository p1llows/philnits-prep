<?php

namespace App\Filament\Resources\TopicResource\Pages;

use App\Filament\Resources\TopicResource;
use App\Filament\Widgets\FieldsSummaryWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTopics extends ListRecords
{
    protected static string $resource = TopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getSubheading(): ?string
    {
        return 'IT Passport syllabus fields, topic categories, and question distribution.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            FieldsSummaryWidget::class,
        ];
    }
}
