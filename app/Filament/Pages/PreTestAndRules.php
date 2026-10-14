<?php

namespace App\Filament\Pages;

use App\Models\SourcePackage;
use App\Services\AppSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PreTestAndRules extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Assessments';

    protected static ?string $navigationLabel = 'Pre-test and rules';

    protected static ?string $title = 'Pre-test and rules';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.pre-test-and-rules';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public ?array $data = [];

    public function mount(AppSettings $settings): void
    {
        $this->form->fill([
            'require_pretest' => $settings->requirePretest(),
            'pretest_source_package_id' => $settings->pretestSourcePackageId(),
            'pretest_question_count' => $settings->pretestQuestionCount(),
            'time_limit_minutes' => $settings->timeLimitMinutes(),
            'allow_pause_resume' => $settings->allowPauseResume(),
            'resume_window_days' => $settings->resumeWindowDays(),
            'shuffle_choices' => $settings->shuffleChoices(),
            'skip_pretest_gate' => $settings->skipPretestGate(),

            'pass_mark_percent' => $settings->passMarkPercent(),
            'field_minimum_percent' => $settings->fieldMinimumPercent(),
            'retake_policy' => $settings->retakePolicy(),

            'weak_topic_threshold_percent' => $settings->weakTopicThresholdPercent(),
            'roadmap_order' => $settings->roadmapOrder(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 1, 'lg' => 2])
                    ->schema([
                        // Left Column: Baseline pre-test
                        Section::make('Baseline pre-test')
                            ->description('Configure the mandatory diagnostic past paper exam for new learners.')
                            ->schema([
                                Toggle::make('require_pretest')
                                    ->label('Require the pre-test')
                                    ->helperText('New learners must finish it before practice unlocks.')
                                    ->default(true),

                                Select::make('pretest_source_package_id')
                                    ->label('Pre-test source package')
                                    ->options(fn () => SourcePackage::pluck('name', 'id')->toArray())
                                    ->placeholder('Select source package (optional)')
                                    ->searchable(),

                                Grid::make(2)->schema([
                                    TextInput::make('pretest_question_count')
                                        ->label('Number of questions')
                                        ->numeric()
                                        ->minValue(1)
                                        ->required()
                                        ->default(100),

                                    TextInput::make('time_limit_minutes')
                                        ->label('Time limit (minutes)')
                                        ->numeric()
                                        ->minValue(1)
                                        ->required()
                                        ->default(120),
                                ]),

                                Toggle::make('allow_pause_resume')
                                    ->label('Allow pause and resume')
                                    ->live()
                                    ->default(true),

                                TextInput::make('resume_window_days')
                                    ->label('Resume window (days)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->visible(fn (Get $get) => (bool) $get('allow_pause_resume'))
                                    ->required(fn (Get $get) => (bool) $get('allow_pause_resume'))
                                    ->default(7),

                                Toggle::make('shuffle_choices')
                                    ->label('Shuffle answer choices')
                                    ->default(false),

                                Toggle::make('skip_pretest_gate')
                                    ->label('Skip the pre-test gate')
                                    ->helperText('Testing only. Lets you use the app without taking the full exam.')
                                    ->default(false),
                            ]),

                        // Right Column: Passing rules & Study roadmap
                        Grid::make(1)
                            ->schema([
                                Section::make('Passing rules')
                                    ->description('Official IT Passport examination passing criteria.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('pass_mark_percent')
                                                ->label('Overall pass mark (%)')
                                                ->numeric()
                                                ->minValue(1)
                                                ->maxValue(100)
                                                ->required()
                                                ->default(60),

                                            TextInput::make('field_minimum_percent')
                                                ->label('Minimum per field (%)')
                                                ->numeric()
                                                ->minValue(1)
                                                ->maxValue(100)
                                                ->required()
                                                ->default(30),
                                        ]),

                                        Select::make('retake_policy')
                                            ->label('Retakes')
                                            ->options([
                                                'unlimited' => 'Unlimited',
                                                'once' => 'Once only',
                                                'weekly' => 'Once per week',
                                            ])
                                            ->required()
                                            ->default('unlimited'),
                                    ]),

                                Section::make('Study roadmap')
                                    ->description('Rules for generating the learner personal review roadmap.')
                                    ->schema([
                                        TextInput::make('weak_topic_threshold_percent')
                                            ->label('A topic counts as weak below (%)')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(100)
                                            ->required()
                                            ->default(50),

                                        Select::make('roadmap_order')
                                            ->label('Order topics by')
                                            ->options([
                                                'weakest_field_first' => 'Weakest field first',
                                                'syllabus_order' => 'Syllabus order',
                                                'most_questions_first' => 'Most questions first',
                                            ])
                                            ->required()
                                            ->default('weakest_field_first'),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(AppSettings $settings): void
    {
        $validated = $this->form->getState();

        $keys = [
            'require_pretest',
            'pretest_source_package_id',
            'pretest_question_count',
            'time_limit_minutes',
            'allow_pause_resume',
            'resume_window_days',
            'shuffle_choices',
            'skip_pretest_gate',
            'pass_mark_percent',
            'field_minimum_percent',
            'retake_policy',
            'weak_topic_threshold_percent',
            'roadmap_order',
        ];

        $oldValues = [];
        foreach ($keys as $k) {
            $oldValues[$k] = $settings->get($k);
        }

        $newValues = [
            'require_pretest' => (bool) ($validated['require_pretest'] ?? true),
            'pretest_source_package_id' => !empty($validated['pretest_source_package_id']) ? (int) $validated['pretest_source_package_id'] : null,
            'pretest_question_count' => (int) ($validated['pretest_question_count'] ?? 100),
            'time_limit_minutes' => (int) ($validated['time_limit_minutes'] ?? 120),
            'allow_pause_resume' => (bool) ($validated['allow_pause_resume'] ?? true),
            'resume_window_days' => (int) ($validated['resume_window_days'] ?? 7),
            'shuffle_choices' => (bool) ($validated['shuffle_choices'] ?? false),
            'skip_pretest_gate' => (bool) ($validated['skip_pretest_gate'] ?? false),

            'pass_mark_percent' => (int) ($validated['pass_mark_percent'] ?? 60),
            'field_minimum_percent' => (int) ($validated['field_minimum_percent'] ?? 30),
            'retake_policy' => (string) ($validated['retake_policy'] ?? 'unlimited'),

            'weak_topic_threshold_percent' => (int) ($validated['weak_topic_threshold_percent'] ?? 50),
            'roadmap_order' => (string) ($validated['roadmap_order'] ?? 'weakest_field_first'),
        ];

        $settings->setMany($newValues);

        \App\Services\Audit::log('rules.updated', 'AppSettings', [
            'old' => $oldValues,
            'new' => $newValues,
        ]);

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
