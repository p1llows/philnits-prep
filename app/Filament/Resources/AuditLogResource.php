<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Audit log';

    protected static ?string $modelLabel = 'audit log';

    protected static ?string $pluralModelLabel = 'audit logs';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime('M j, Y H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Who')
                    ->default('System')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains($state, 'rejected') || str_contains($state, 'deactivated') => 'danger',
                        str_contains($state, 'approved') || str_contains($state, 'published') || str_contains($state, 'reactivated') => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucwords(str_replace(['.', '_'], ' ', $state)))
                    ->searchable(),

                Tables\Columns\TextColumn::make('target')
                    ->label('Target')
                    ->getStateUsing(function (AuditLog $record) {
                        if (empty($record->target_type)) {
                            return '—';
                        }
                        $shortType = class_basename($record->target_type);
                        return $record->target_id ? "{$shortType} #{$record->target_id}" : $shortType;
                    })
                    ->searchable(['target_type', 'target_id']),

                Tables\Columns\TextColumn::make('detail')
                    ->label('Detail')
                    ->limit(60)
                    ->getStateUsing(function (AuditLog $record) {
                        if (empty($record->detail)) {
                            return '—';
                        }
                        $json = json_encode($record->detail, JSON_UNESCAPED_SLASHES);
                        return mb_strlen($json) > 80 ? mb_substr($json, 0, 77) . '...' : $json;
                    })
                    ->tooltip(fn (AuditLog $record) => !empty($record->detail) ? json_encode($record->detail, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : null),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Category')
                    ->options([
                        'content' => 'Content',
                        'users' => 'Users',
                        'rules' => 'Rules',
                        'settings' => 'Settings',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $val = $data['value'] ?? null;
                        if (!$val) {
                            return $query;
                        }
                        return match ($val) {
                            'content' => $query->where(function ($q) {
                                $q->where('action', 'like', 'question.%')
                                  ->orWhere('action', 'like', 'package.%')
                                  ->orWhere('action', 'like', 'topic.%');
                            }),
                            'users' => $query->where(function ($q) {
                                $q->where('action', 'like', 'user.%')
                                  ->orWhere('action', 'like', 'role.%');
                            }),
                            'rules' => $query->where(function ($q) {
                                $q->where('action', 'like', 'rules.%')
                                  ->orWhere('action', 'like', 'pretest.%');
                            }),
                            'settings' => $query->where('action', 'like', 'settings.%'),
                            default => $query,
                        };
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $records = AuditLog::with('user')->latest()->get();

                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => 'attachment; filename="audit-log-' . date('Y-m-d-His') . '.csv"',
                        ];

                        $callback = function () use ($records) {
                            $handle = fopen('php://output', 'w');
                            fputcsv($handle, ['ID', 'When', 'Who', 'Action', 'Target Type', 'Target ID', 'Detail']);

                            foreach ($records as $log) {
                                fputcsv($handle, [
                                    $log->id,
                                    $log->created_at?->toIso8601String(),
                                    $log->user?->name ?? 'System',
                                    $log->action,
                                    $log->target_type,
                                    $log->target_id,
                                    json_encode($log->detail, JSON_UNESCAPED_SLASHES),
                                ]);
                            }

                            fclose($handle);
                        };

                        return response()->stream($callback, 200, $headers);
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Audit log entry')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('created_at')
                            ->label('When')
                            ->formatStateUsing(fn ($record) => $record?->created_at?->toDateTimeString()),
                        \Filament\Forms\Components\TextInput::make('who')
                            ->label('Who')
                            ->formatStateUsing(fn ($record) => $record?->user?->name ?? 'System'),
                        \Filament\Forms\Components\TextInput::make('action')
                            ->label('Action'),
                        \Filament\Forms\Components\TextInput::make('target_type')
                            ->label('Target type'),
                        \Filament\Forms\Components\TextInput::make('target_id')
                            ->label('Target ID'),
                        \Filament\Forms\Components\Textarea::make('detail_json')
                            ->label('Detail (JSON)')
                            ->rows(6)
                            ->formatStateUsing(fn ($record) => json_encode($record?->detail, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
        ];
    }
}
