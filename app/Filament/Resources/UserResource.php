<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Mail\SendOtpMail;
use App\Models\Assessment;
use App\Models\User;
use App\Services\Audit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'People';

    protected static ?string $navigationLabel = 'Users and roles';

    protected static ?string $modelLabel = 'user';

    protected static ?string $pluralModelLabel = 'users';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'editor' => 'Editor',
                        'learner' => 'Learner',
                    ])
                    ->default('learner')
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active account')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user')
                    ->label('User')
                    ->wrap()
                    ->getStateUsing(fn (User $record) => $record->name)
                    ->description(fn (User $record) => $record->email)
                    ->searchable(['name', 'email'])
                    ->sortable(['name']),

                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->width('100px')
                    ->alignCenter()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'primary',
                        'editor' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('pretest_status')
                    ->label('Pre-test')
                    ->width('140px')
                    ->badge()
                    ->getStateUsing(function (User $record) {
                        $latest = $record->assessments()
                            ->whereNull('archived_at')
                            ->whereIn('assessment_type', ['pretest', 'initial'])
                            ->latest()
                            ->first();

                        if (!$latest) {
                            return 'Not started';
                        }

                        if (in_array($latest->status, ['submitted', 'graded']) || !empty($latest->completed_at)) {
                            return 'Completed';
                        }

                        $answeredCount = $latest->answers()->count();
                        return "In progress ({$answeredCount} answered)";
                    })
                    ->color(fn (string $state): string => match (true) {
                        str_starts_with($state, 'Completed') => 'success',
                        str_starts_with($state, 'In progress') => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('baseline_score')
                    ->label('Baseline score')
                    ->width('110px')
                    ->alignCenter()
                    ->getStateUsing(function (User $record) {
                        $completed = $record->assessments()
                            ->whereNull('archived_at')
                            ->whereIn('assessment_type', ['pretest', 'initial'])
                            ->whereIn('status', ['submitted', 'graded'])
                            ->latest()
                            ->first();

                        return $completed && $completed->score_percentage !== null
                            ? round($completed->score_percentage, 1) . '%'
                            : '—';
                    }),

                Tables\Columns\TextColumn::make('weakest_field')
                    ->label('Weakest field')
                    ->width('130px')
                    ->alignCenter()
                    ->getStateUsing(function (User $record) {
                        $completed = $record->assessments()
                            ->whereNull('archived_at')
                            ->whereIn('assessment_type', ['pretest', 'initial'])
                            ->whereIn('status', ['submitted', 'graded'])
                            ->latest()
                            ->first();

                        if (!$completed || empty($completed->field_performance)) {
                            return '—';
                        }

                        $minField = null;
                        $minPct = 101;

                        foreach ($completed->field_performance as $field => $data) {
                            $pct = $data['percentage'] ?? 0;
                            if ($pct < $minPct) {
                                $minPct = $pct;
                                $minField = ucfirst($field);
                            }
                        }

                        return $minField ? "{$minField} ({$minPct}%)" : '—';
                    }),

                Tables\Columns\TextColumn::make('last_active_at')
                    ->label('Last active')
                    ->width('130px')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('Never')
                    ->sortable(),
            ])
            ->emptyStateHeading('No users found')
            ->emptyStateDescription('Create users or invite learners to begin diagnostic exam practice.')
            ->emptyStateIcon('heroicon-o-users')
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'editor' => 'Editor',
                        'learner' => 'Learner',
                    ]),

                Tables\Filters\SelectFilter::make('pretest_status')
                    ->label('Pre-test status')
                    ->options([
                        'not_started' => 'Not started',
                        'in_progress' => 'In progress',
                        'completed' => 'Completed',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $val = $data['value'] ?? null;
                        if (!$val) {
                            return $query;
                        }

                        if ($val === 'not_started') {
                            return $query->whereDoesntHave('assessments', function ($q) {
                                $q->whereNull('archived_at')->where('assessment_type', 'pretest');
                            });
                        }

                        if ($val === 'completed') {
                            return $query->whereHas('assessments', function ($q) {
                                $q->whereNull('archived_at')
                                  ->where('assessment_type', 'pretest')
                                  ->whereIn('status', ['submitted', 'graded']);
                            });
                        }

                        if ($val === 'in_progress') {
                            return $query->whereHas('assessments', function ($q) {
                                $q->whereNull('archived_at')
                                  ->where('assessment_type', 'pretest')
                                  ->whereNotIn('status', ['submitted', 'graded']);
                            });
                        }

                        return $query;
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create_user')
                    ->label('Create user')
                    ->icon('heroicon-o-user-plus')
                    ->modalHeading('Create user with role')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Full name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->unique(table: 'users', column: 'email'),
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'editor' => 'Editor',
                                'learner' => 'Learner',
                            ])
                            ->default('learner')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'role' => $data['role'],
                            'password' => Hash::make(Str::random(32)),
                            'is_active' => true,
                        ]);

                        Audit::log('user.created', $user, [
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $user->role,
                        ]);

                        // Send verification code
                        $otpResult = app(\App\Services\OtpService::class)->generate($user->email);
                        if (!empty($otpResult['success']) && !empty($otpResult['code'])) {
                            Mail::to($user->email)->send(new SendOtpMail($otpResult['code'], $user->name));
                        }

                        Notification::make()
                            ->title('User created successfully')
                            ->body('Verification email has been sent.')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('change_role')
                        ->label('Change role')
                        ->icon('heroicon-o-identification')
                        ->form([
                            Forms\Components\Select::make('role')
                                ->label('Select new role')
                                ->options([
                                    'admin' => 'Admin',
                                    'editor' => 'Editor',
                                    'learner' => 'Learner',
                                ])
                                ->required()
                                ->default(fn (User $record) => $record->role),
                        ])
                        ->action(function (User $record, array $data) {
                            $newRole = $data['role'];

                            // Safety 1: Cannot demote self
                            if ($record->id === auth()->id() && $newRole !== 'admin') {
                                Notification::make()
                                    ->title('Action blocked')
                                    ->body('You cannot demote yourself from admin.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Safety 2: At least one active admin must remain
                            if ($record->role === 'admin' && $newRole !== 'admin') {
                                $activeAdminsCount = User::where('role', 'admin')
                                    ->where('is_active', true)
                                    ->where('id', '!=', $record->id)
                                    ->count();

                                if ($activeAdminsCount < 1) {
                                    Notification::make()
                                        ->title('Action blocked')
                                        ->body('At least one active admin must remain.')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                            }

                            $oldRole = $record->role;
                            $record->update(['role' => $newRole]);

                            Audit::log('role.changed', $record, [
                                'user_id' => $record->id,
                                'old_role' => $oldRole,
                                'new_role' => $newRole,
                            ]);

                            Notification::make()
                                ->title("Role updated to {$newRole}")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Deactivate user account')
                        ->modalDescription(fn (User $record) => "Are you sure you want to deactivate {$record->name}? They will no longer be able to sign in.")
                        ->visible(fn (User $record) => (bool) $record->is_active)
                        ->action(function (User $record) {
                            // Safety 1: Cannot deactivate self
                            if ($record->id === auth()->id()) {
                                Notification::make()
                                    ->title('Action blocked')
                                    ->body('You cannot deactivate your own account.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Safety 2: At least one active admin must remain
                            if ($record->role === 'admin') {
                                $activeAdminsCount = User::where('role', 'admin')
                                    ->where('is_active', true)
                                    ->where('id', '!=', $record->id)
                                    ->count();

                                if ($activeAdminsCount < 1) {
                                    Notification::make()
                                        ->title('Action blocked')
                                        ->body('At least one active admin must remain.')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                            }

                            $record->update(['is_active' => false]);

                            Audit::log('user.deactivated', $record, [
                                'user_id' => $record->id,
                                'email' => $record->email,
                            ]);

                            Notification::make()
                                ->title('User account deactivated')
                                ->warning()
                                ->send();
                        }),

                    Tables\Actions\Action::make('reactivate')
                        ->label('Reactivate')
                        ->icon('heroicon-o-check-circle')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->visible(fn (User $record) => ! (bool) $record->is_active)
                        ->action(function (User $record) {
                            $record->update(['is_active' => true]);

                            Audit::log('user.reactivated', $record, [
                                'user_id' => $record->id,
                                'email' => $record->email,
                            ]);

                            Notification::make()
                                ->title('User account reactivated')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('resend_verification')
                        ->label('Resend email')
                        ->icon('heroicon-o-envelope')
                        ->action(function (User $record) {
                            $otpResult = app(\App\Services\OtpService::class)->generate($record->email);
                            if (!empty($otpResult['success']) && !empty($otpResult['code'])) {
                                Mail::to($record->email)->send(new SendOtpMail($otpResult['code'], $record->name));
                            }

                            Notification::make()
                                ->title('Verification email sent')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('reset_pretest')
                        ->label('Reset pre-test')
                        ->icon('heroicon-o-arrow-path')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Reset pre-test')
                        ->modalDescription(fn (User $record) => "Are you sure you want to reset {$record->name}'s pre-test? Their past attempts will be safely archived and they will take a fresh baseline pre-test.")
                        ->action(function (User $record) {
                            $count = $record->assessments()
                                ->whereNull('archived_at')
                                ->update(['archived_at' => now()]);

                            Audit::log('pretest.reset', $record, [
                                'user_id' => $record->id,
                                'archived_assessments_count' => $count,
                            ]);

                            Notification::make()
                                ->title("Pre-test reset ({$count} attempts archived)")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
