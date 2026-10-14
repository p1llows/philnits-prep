<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Models\Question;
use App\Models\SourcePackage;
use App\Models\Topic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Questions';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Question details')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('source_package_id')
                                ->label('Source package')
                                ->relationship('sourcePackage', 'name')
                                ->searchable()
                                ->preload(),

                            Forms\Components\TextInput::make('source_question_number')
                                ->label('Q number')
                                ->numeric(),

                            Forms\Components\TextInput::make('source_page')
                                ->label('Source page')
                                ->numeric(),
                        ]),

                        Forms\Components\Textarea::make('question_text')
                            ->label('Question text')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('code_block')
                            ->label('Code block (optional monospace snippet)')
                            ->rows(3)
                            ->columnSpanFull()
                            ->extraInputAttributes(['class' => 'font-mono']),

                        Forms\Components\FileUpload::make('image_path')
                            ->label('Question image (optional)')
                            ->disk('public')
                            ->directory('questions/images')
                            ->image()
                            ->maxSize(5120),
                    ]),

                Forms\Components\Section::make('Choices')
                    ->description('Enter choice options and select the correct answer.')
                    ->schema([
                        Forms\Components\Select::make('correct_answer_code')
                            ->label('Correct answer')
                            ->options([
                                'A' => 'Choice A',
                                'B' => 'Choice B',
                                'C' => 'Choice C',
                                'D' => 'Choice D',
                            ])
                            ->required(),

                        Forms\Components\Repeater::make('choices')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Code')
                                    ->required()
                                    ->maxLength(5),
                                Forms\Components\TextInput::make('text')
                                    ->label('Choice text')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(4)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Classification & Status')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('topic_id')
                                ->label('Syllabus topic')
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('answer_source')
                                ->label('Answer source')
                                ->options([
                                    'official_key' => 'Official key',
                                    'model_suggestion' => 'Model suggestion',
                                    'manual' => 'Manual',
                                ])
                                ->default('manual')
                                ->required(),

                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'in_review' => 'In review',
                                    'approved' => 'Approved',
                                    'published' => 'Published',
                                    'rejected' => 'Rejected',
                                ])
                                ->default('published')
                                ->required(),
                        ]),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('explanation_status')
                                ->label('Explanation status')
                                ->options([
                                    'none' => 'None',
                                    'ai_draft' => 'AI draft',
                                    'approved' => 'Approved',
                                ])
                                ->default('none'),

                            Forms\Components\TextInput::make('difficulty')
                                ->placeholder('easy, medium, hard'),
                        ]),

                        Forms\Components\Textarea::make('explanation')
                            ->label('Explanation')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('review_note')
                            ->label('Review note')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_question_number')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question')
                    ->limit(65)
                    ->searchable(),

                Tables\Columns\TextColumn::make('topic.field_label')
                    ->label('Field')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topic')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sourcePackage.name')
                    ->label('Source')
                    ->formatStateUsing(function (Question $record) {
                        $label = $record->sourcePackage?->exam_session_label ?: $record->sourcePackage?->name;
                        return $label ? "{$label} (Q{$record->source_question_number})" : "Q{$record->source_question_number}";
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('correct_rate')
                    ->label('Correct rate')
                    ->getStateUsing(function (Question $record) {
                        $total = $record->assessmentAnswers()->count();
                        if ($total < 10) {
                            return 'n/a';
                        }
                        $correct = $record->assessmentAnswers()->where('is_correct', true)->count();
                        $rate = round(($correct / $total) * 100, 1);
                        return "{$rate}%";
                    })
                    ->badge()
                    ->color(function (Question $record) {
                        $total = $record->assessmentAnswers()->count();
                        if ($total < 10) {
                            return 'gray';
                        }
                        $correct = $record->assessmentAnswers()->where('is_correct', true)->count();
                        $rate = ($correct / $total) * 100;
                        return $rate < 20 ? 'danger' : 'gray';
                    })
                    ->description(function (Question $record) {
                        $total = $record->assessmentAnswers()->count();
                        if ($total >= 10) {
                            $correct = $record->assessmentAnswers()->where('is_correct', true)->count();
                            $rate = ($correct / $total) * 100;
                            if ($rate < 20) {
                                return 'Check key';
                            }
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'primary',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'in_review' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'Draft',
                        'in_review' => 'In review',
                        'approved' => 'Approved',
                        'published' => 'Published',
                        'rejected' => 'Rejected',
                        default => ucfirst($state),
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('field')
                    ->label('Field')
                    ->relationship('topic', 'field')
                    ->options([
                        'strategy' => 'Strategy',
                        'management' => 'Management',
                        'technology' => 'Technology',
                    ]),

                Tables\Filters\SelectFilter::make('topic_id')
                    ->label('Topic')
                    ->relationship('topic', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'in_review' => 'In review',
                        'approved' => 'Approved',
                        'published' => 'Published',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('source_package_id')
                    ->label('Source package')
                    ->relationship('sourcePackage', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('set_status')
                        ->label('Set status')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'in_review' => 'In review',
                                    'approved' => 'Approved',
                                    'published' => 'Published',
                                    'rejected' => 'Rejected',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each->update(['status' => $data['status']]);
                            Notification::make()->title('Status updated for selected questions')->success()->send();
                        }),

                    Tables\Actions\BulkAction::make('export_csv')
                        ->label('Export CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            $headers = [
                                'Content-Type' => 'text/csv',
                                'Content-Disposition' => 'attachment; filename="questions-export.csv"',
                            ];

                            $callback = function () use ($records) {
                                $handle = fopen('php://output', 'w');
                                fputcsv($handle, ['ID', 'Number', 'Question Text', 'Field', 'Topic', 'Answer Code', 'Status']);

                                foreach ($records as $q) {
                                    fputcsv($handle, [
                                        $q->id,
                                        $q->source_question_number,
                                        $q->question_text,
                                        $q->topic?->field,
                                        $q->topic?->name,
                                        $q->correct_answer_code,
                                        $q->status,
                                    ]);
                                }

                                fclose($handle);
                            };

                            return response()->stream($callback, 200, $headers);
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
