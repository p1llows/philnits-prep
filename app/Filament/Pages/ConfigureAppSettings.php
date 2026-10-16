<?php

namespace App\Filament\Pages;

use App\Services\AppSettings;
use App\Services\Audit;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ConfigureAppSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Configure';

    protected static ?string $navigationLabel = 'App settings';

    protected static ?string $title = 'App settings';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.configure-app-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function getSubheading(): ?string
    {
        return 'Global branding, learner registration access, verification limits, and announcement notices.';
    }

    public function mount(AppSettings $settings): void
    {
        $this->form->fill([
            // Brand
            'app_name' => $settings->get('app_name', config('app.name', 'Paxi')),
            'app_tagline' => $settings->get('app_tagline', 'IT Passport Study Companion'),
            'app_logo_path' => $settings->get('app_logo_path'),
            'app_favicon_path' => $settings->get('app_favicon_path'),

            // Sign-in and verification
            'open_registration' => (bool) $settings->get('open_registration', true),
            'otp_expiration_minutes' => (int) $settings->get('otp_expiration_minutes', 10),
            'otp_resend_cooldown_seconds' => (int) $settings->get('otp_resend_cooldown_seconds', 60),
            'otp_max_attempts' => (int) $settings->get('otp_max_attempts', 5),

            // Welcome page content
            'welcome_faq_items' => $settings->get('welcome_faq_items', []),
            'welcome_disclaimer_text' => $settings->get('welcome_disclaimer_text', ''),

            // Verification email
            'mail_sender_name' => $settings->get('mail_sender_name', config('app.name', 'Paxi')),
            'mail_subject' => $settings->get('mail_subject', 'Your Paxi verification code'),

            // Announcement banner
            'announcement_enabled' => (bool) $settings->get('announcement_enabled', false),
            'announcement_text' => $settings->get('announcement_text', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Brand')
                    ->description('Application name, tagline and visual assets')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('app_name')
                                ->label('App name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('app_tagline')
                                ->label('App tagline')
                                ->maxLength(255),

                            FileUpload::make('app_logo_path')
                                ->label('Logo')
                                ->disk('public')
                                ->directory('brand')
                                ->image()
                                ->imagePreviewHeight('64px')
                                ->acceptedFileTypes(['image/svg+xml', 'image/png'])
                                ->maxSize(2048)
                                ->helperText('Current default: /paxi-mark.svg'),

                            FileUpload::make('app_favicon_path')
                                ->label('Favicon')
                                ->disk('public')
                                ->directory('brand')
                                ->image()
                                ->imagePreviewHeight('48px')
                                ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/x-icon'])
                                ->maxSize(1024)
                                ->helperText('Current default: /favicon.svg'),
                        ]),
                    ]),

                Section::make('Sign-in and verification')
                    ->description('Control authentication rules, OTP expiry, and rate limits')
                    ->schema([
                        Toggle::make('open_registration')
                            ->label('Open registration')
                            ->helperText('When turned off, new learners cannot sign up.'),

                        Grid::make(3)->schema([
                            TextInput::make('otp_expiration_minutes')
                                ->label('OTP code expiry (minutes)')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(60)
                                ->required()
                                ->default(10),

                            TextInput::make('otp_resend_cooldown_seconds')
                                ->label('Resend cooldown (seconds)')
                                ->numeric()
                                ->minValue(10)
                                ->maxValue(300)
                                ->required()
                                ->default(60),

                            TextInput::make('otp_max_attempts')
                                ->label('Max attempts')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10)
                                ->required()
                                ->default(5),
                        ]),
                    ]),

                Section::make('Verification email')
                    ->description('Email notification sender and subject settings')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('mail_sender_name')
                                ->label('Sender name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('mail_subject')
                                ->label('Subject')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Subject line for the OTP email. Note: the verification code is never included in the subject.'),
                        ]),
                    ]),

                Section::make('Announcement banner')
                    ->description('Display a slim announcement bar below the learner navigation bar')
                    ->schema([
                        Toggle::make('announcement_enabled')
                            ->label('Show announcement banner'),

                        TextInput::make('announcement_text')
                            ->label('Banner message')
                            ->maxLength(255)
                            ->helperText('Plain text shown in a hairline border paper background.'),
                    ]),

                Section::make('Welcome page content')
                    ->description('Custom FAQ items and footer disclaimer')
                    ->schema([
                        Repeater::make('welcome_faq_items')
                            ->label('FAQ items')
                            ->schema([
                                TextInput::make('question')
                                    ->label('Question')
                                    ->required(),
                                Textarea::make('answer')
                                    ->label('Answer')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null),

                        Textarea::make('welcome_disclaimer_text')
                            ->label('Disclaimer text')
                            ->rows(3)
                            ->helperText('Custom disclaimer displayed above the footer on the welcome page.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(AppSettings $settings): void
    {
        $validated = $this->form->getState();

        // Redact any possible sensitive field if present, though none here
        $keys = [
            'app_name',
            'app_tagline',
            'app_logo_path',
            'app_favicon_path',
            'open_registration',
            'otp_expiration_minutes',
            'otp_resend_cooldown_seconds',
            'otp_max_attempts',
            'welcome_faq_items',
            'welcome_disclaimer_text',
            'mail_sender_name',
            'mail_subject',
            'announcement_enabled',
            'announcement_text',
        ];

        $oldValues = [];
        foreach ($keys as $k) {
            $oldValues[$k] = $settings->get($k);
        }

        $newValues = [
            'app_name' => (string) ($validated['app_name'] ?? 'Paxi'),
            'app_tagline' => (string) ($validated['app_tagline'] ?? ''),
            'app_logo_path' => $validated['app_logo_path'] ?? null,
            'app_favicon_path' => $validated['app_favicon_path'] ?? null,
            'open_registration' => (bool) ($validated['open_registration'] ?? true),
            'otp_expiration_minutes' => (int) ($validated['otp_expiration_minutes'] ?? 10),
            'otp_resend_cooldown_seconds' => (int) ($validated['otp_resend_cooldown_seconds'] ?? 60),
            'otp_max_attempts' => (int) ($validated['otp_max_attempts'] ?? 5),
            'welcome_faq_items' => $validated['welcome_faq_items'] ?? [],
            'welcome_disclaimer_text' => (string) ($validated['welcome_disclaimer_text'] ?? ''),
            'mail_sender_name' => (string) ($validated['mail_sender_name'] ?? 'Paxi'),
            'mail_subject' => (string) ($validated['mail_subject'] ?? 'Your Paxi verification code'),
            'announcement_enabled' => (bool) ($validated['announcement_enabled'] ?? false),
            'announcement_text' => (string) ($validated['announcement_text'] ?? ''),
        ];

        $settings->setMany($newValues);

        Audit::log('settings.updated', 'AppSettings', [
            'old' => $oldValues,
            'new' => $newValues,
        ]);

        Notification::make()
            ->title('App settings saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save changes')
                ->submit('save'),
        ];
    }
}
