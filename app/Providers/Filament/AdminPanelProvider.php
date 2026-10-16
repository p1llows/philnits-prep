<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Paxi')
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('1.75rem')
            ->favicon(asset('favicon.svg'))
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth('7xl')
            ->topNavigation(false)
            ->breadcrumbs(false)
            ->font('Inter')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->darkMode(false)
            ->profile(isSimple: false)
            ->colors([
                'primary' => [
                    50 => '#F0F4F9',
                    100 => '#E6ECF4',
                    200 => '#CBD7E7',
                    300 => '#A5B8D3',
                    400 => '#6F8DB8',
                    500 => '#3A5F92',
                    600 => '#1F3A5F',
                    700 => '#1A3253',
                    800 => '#152944',
                    900 => '#101F34',
                    950 => '#0A1423',
                ],
                'gray' => [
                    50 => '#F6F4EF',
                    100 => '#EFECE4',
                    200 => '#E3DFD5',
                    300 => '#CFCABD',
                    400 => '#A29D91',
                    500 => '#6E6A60',
                    600 => '#57534A',
                    700 => '#433F38',
                    800 => '#2E2B26',
                    900 => '#1B1A17',
                    950 => '#100F0D',
                ],
                'success' => Color::hex('#2F6B4F'),
                'danger' => Color::hex('#A3392F'),
                'warning' => [
                    50 => '#F6F4EF',
                    100 => '#EFECE4',
                    200 => '#E3DFD5',
                    300 => '#CFCABD',
                    400 => '#A29D91',
                    500 => '#6E6A60',
                    600 => '#57534A',
                    700 => '#433F38',
                    800 => '#2E2B26',
                    900 => '#1B1A17',
                    950 => '#100F0D',
                ],
                'info' => [
                    50 => '#F0F4F9',
                    100 => '#E6ECF4',
                    200 => '#CBD7E7',
                    300 => '#A5B8D3',
                    400 => '#6F8DB8',
                    500 => '#3A5F92',
                    600 => '#1F3A5F',
                    700 => '#1A3253',
                    800 => '#152944',
                    900 => '#101F34',
                    950 => '#0A1423',
                ],
            ])
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn () => view('filament.shortcuts'),
            )
            ->navigationGroups([
                NavigationGroup::make('Overview')->collapsible(false),
                NavigationGroup::make('Content')->collapsible(false),
                NavigationGroup::make('People')->collapsible(false),
                NavigationGroup::make('Assessments')->collapsible(false),
                NavigationGroup::make('Insights')->collapsible(false),
                NavigationGroup::make('Configure')->collapsible(false),
                NavigationGroup::make('System')->collapsible(false),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
