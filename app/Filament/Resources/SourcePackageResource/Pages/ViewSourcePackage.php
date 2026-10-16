<?php

namespace App\Filament\Resources\SourcePackageResource\Pages;

use App\Filament\Resources\QuestionResource;
use App\Filament\Resources\SourcePackageResource;
use App\Models\Question;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class ViewSourcePackage extends ViewRecord implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = SourcePackageResource::class;

    protected static string $view = 'filament.resources.source-package-resource.pages.view-source-package';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('publish_approved')
                ->label('Publish approved questions')
                ->icon('heroicon-o-check-badge')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Publish approved questions')
                ->modalDescription('Are you sure you want to publish all approved questions in this package to learners? Questions without an official answer key or pending review will not be published.')
                ->visible(fn () => !empty($this->record->answer_key_path))
                ->action(function () {
                    if (empty($this->record->answer_key_path)) {
                        Notification::make()
                            ->title('Answer key missing')
                            ->body('An official answer key is required before publishing questions.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $count = $this->record->questions()
                        ->where('status', 'approved')
                        ->update([
                            'status' => 'published',
                            'published_at' => now(),
                            'published_by' => auth()->id(),
                        ]);

                    $totalPublished = $this->record->questions()->where('status', 'published')->count();
                    $this->record->update([
                        'published_count' => $totalPublished,
                        'status' => $totalPublished > 0 ? 'published' : $this->record->status,
                    ]);

                    Notification::make()
                        ->title("Published {$count} approved questions")
                        ->success()
                        ->send();
                }),

            Actions\EditAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn () => $this->record->questions())
            ->columns([
                Tables\Columns\TextColumn::make('source_question_number')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question')
                    ->limit(60)
                    ->searchable(),
                Tables\Columns\TextColumn::make('topic.field_label')
                    ->label('Field')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topic')
                    ->searchable(),
                Tables\Columns\TextColumn::make('answer_source')
                    ->label('Answer source')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'official_key' => 'Official key',
                        'model_suggestion' => 'Model suggestion',
                        default => 'Manual',
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'official_key' => 'success',
                        'model_suggestion' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'published' => 'primary',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'in_review' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('edit_question')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Question $record) => QuestionResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
