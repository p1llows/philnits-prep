<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicResource\Pages;
use App\Models\Topic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Topics and fields';

    protected static ?string $modelLabel = 'topic';

    protected static ?string $pluralModelLabel = 'Topics and fields';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Topic details')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Topic name')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('field')
                                ->label('Field')
                                ->options([
                                    'strategy' => 'Strategy (S)',
                                    'management' => 'Management (M)',
                                    'technology' => 'Technology (T)',
                                ])
                                ->required(),

                            Forms\Components\TextInput::make('code')
                                ->label('Topic code')
                                ->placeholder('e.g. STR-01')
                                ->maxLength(50),
                        ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('color')
                                ->label('Color hex')
                                ->placeholder('e.g. 1F3A5F')
                                ->default('1F3A5F'),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Active in curriculum')
                                ->default(true),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('field_label')
                    ->label('Field')
                    ->badge()
                    ->color('gray')
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('field', $direction)),

                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('field')
                    ->options([
                        'strategy' => 'Strategy',
                        'management' => 'Management',
                        'technology' => 'Technology',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}
