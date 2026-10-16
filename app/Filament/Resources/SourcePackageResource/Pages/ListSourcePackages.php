<?php

namespace App\Filament\Resources\SourcePackageResource\Pages;

use App\Filament\Resources\SourcePackageResource;
use App\Models\SourcePackage;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSourcePackages extends ListRecords
{
    protected static string $resource = SourcePackageResource::class;

    public function getSubheading(): ?string
    {
        return 'Upload and manage official IT Passport past papers and examination packages.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('upload_past_paper')
                ->label('Upload past paper')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->form([
                    Forms\Components\TextInput::make('name')
                        ->label('Package name')
                        ->placeholder('e.g. IT Passport Autumn 2024')
                        ->required(),

                    Forms\Components\TextInput::make('exam_session_label')
                        ->label('Exam session label')
                        ->placeholder('e.g. October 2024 Session'),

                    Forms\Components\Select::make('use')
                        ->label('Use')
                        ->options([
                            'practice' => 'Practice',
                            'pretest' => 'Pretest',
                        ])
                        ->default('practice')
                        ->required(),

                    Forms\Components\FileUpload::make('paper_path')
                        ->label('Question paper (PDF)')
                        ->disk('local')
                        ->directory('source_packages/papers')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(51200)
                        ->required(),

                    Forms\Components\FileUpload::make('answer_key_path')
                        ->label('Official answer key (PDF)')
                        ->disk('local')
                        ->directory('source_packages/answers')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(51200)
                        ->helperText('The answer key is required before approved questions can be published.'),

                    Forms\Components\Textarea::make('notes')
                        ->label('Notes')
                        ->rows(2),
                ])
                ->action(function (array $data): void {
                    $package = SourcePackage::create([
                        'name' => $data['name'],
                        'exam_session_label' => $data['exam_session_label'] ?? null,
                        'use' => $data['use'] ?? 'practice',
                        'paper_path' => $data['paper_path'] ?? null,
                        'answer_key_path' => $data['answer_key_path'] ?? null,
                        'notes' => $data['notes'] ?? null,
                        'status' => 'uploaded',
                        'is_official' => true,
                        'created_by' => auth()->id(),
                    ]);

                    Notification::make()
                        ->title('Past paper uploaded successfully')
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make()
                ->label('New package'),
        ];
    }
}
