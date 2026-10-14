<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SourcePackageResource\Pages;
use App\Models\SourcePackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SourcePackageResource extends Resource
{
    protected static ?string $model = SourcePackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Source packages';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package details')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Package name')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('exam_session_label')
                                ->label('Exam session label')
                                ->placeholder('e.g. October 2024 Exam')
                                ->maxLength(255),
                        ]),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'uploaded' => 'Uploaded',
                                    'parsing' => 'Parsing',
                                    'in_review' => 'In review',
                                    'published' => 'Published',
                                    'failed' => 'Failed',
                                ])
                                ->default('uploaded')
                                ->required(),

                            Forms\Components\Select::make('use')
                                ->label('Use')
                                ->options([
                                    'practice' => 'Practice',
                                    'pretest' => 'Pretest',
                                ])
                                ->default('practice')
                                ->required(),

                            Forms\Components\Toggle::make('is_official')
                                ->label('Official ITPEC paper')
                                ->default(true),
                        ]),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('PDF documents')
                    ->description('Upload the original examination booklet and answer key on the private disk.')
                    ->schema([
                        Forms\Components\FileUpload::make('paper_path')
                            ->label('Question paper (PDF)')
                            ->disk('local')
                            ->directory('source_packages/papers')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(51200),

                        Forms\Components\FileUpload::make('answer_key_path')
                            ->label('Official answer key (PDF)')
                            ->disk('local')
                            ->directory('source_packages/answers')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(51200)
                            ->helperText('Required before approved questions can be published.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Package')
                    ->description(fn (SourcePackage $record) => $record->exam_session_label)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'primary',
                        'in_review' => 'gray',
                        'parsing' => 'gray',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'uploaded' => 'Uploaded',
                        'parsing' => 'Parsing',
                        'in_review' => 'In review',
                        'published' => 'Published',
                        'failed' => 'Failed',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('questions_progress')
                    ->label('Questions')
                    ->getStateUsing(function (SourcePackage $record) {
                        $approved = $record->questions()->whereIn('status', ['approved', 'published'])->count();
                        $total = $record->questions()->count();
                        return "{$approved} / {$total}";
                    }),

                Tables\Columns\TextColumn::make('use')
                    ->label('Use')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pretest' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Open')
                    ->icon('heroicon-o-eye')
                    ->url(fn (SourcePackage $record) => Pages\ViewSourcePackage::getUrl(['record' => $record])),

                Tables\Actions\Action::make('publish_approved')
                    ->label('Publish approved questions')
                    ->icon('heroicon-o-check-badge')
                    ->requiresConfirmation()
                    ->modalHeading('Publish approved questions')
                    ->modalDescription('Are you sure you want to publish all approved questions in this package to learners? Questions without an official answer key or pending review will not be published.')
                    ->visible(fn (SourcePackage $record) => !empty($record->answer_key_path))
                    ->action(function (SourcePackage $record) {
                        if (empty($record->answer_key_path)) {
                            Notification::make()
                                ->title('Answer key missing')
                                ->body('The answer key is required before the package can be published.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $count = $record->questions()
                            ->where('status', 'approved')
                            ->update([
                                'status' => 'published',
                                'published_at' => now(),
                                'published_by' => auth()->id(),
                            ]);

                        $totalPublished = $record->questions()->where('status', 'published')->count();
                        $record->update([
                            'published_count' => $totalPublished,
                            'status' => $totalPublished > 0 ? 'published' : $record->status,
                        ]);

                        \App\Services\Audit::log('package.published', $record, [
                            'package_id' => $record->id,
                            'published_questions' => $count,
                            'total_published' => $totalPublished,
                        ]);

                        Notification::make()
                            ->title("Published {$count} approved questions")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
            ]);
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
            'index' => Pages\ListSourcePackages::route('/'),
            'create' => Pages\CreateSourcePackage::route('/create'),
            'view' => Pages\ViewSourcePackage::route('/{record}'),
            'edit' => Pages\EditSourcePackage::route('/{record}/edit'),
        ];
    }
}
