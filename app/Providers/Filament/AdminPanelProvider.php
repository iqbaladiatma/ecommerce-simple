<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
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
            ->login()
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
                \App\Http\Middleware\AdminMiddleware::class,
                Authenticate::class,
            ])
            ->brandName('ShopHub')
            ->brandLogo(asset('images/logo.svg'))
            ->favicon(asset('images/favicon.svg'))
            ->navigationGroups([
                'Shop Management',
                'User Management',
                'System',
            ])
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups()
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Dashboard')
                    ->icon('heroicon-o-home')
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.pages.dashboard'))
                    ->url(fn(): string => route('filament.admin.pages.dashboard')),
                \Filament\Navigation\NavigationItem::make('Products')
                    ->icon('heroicon-o-shopping-bag')
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.resources.products.*'))
                    ->url(fn(): string => route('filament.admin.resources.products.index')),
                \Filament\Navigation\NavigationItem::make('Orders')
                    ->icon('heroicon-o-shopping-cart')
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.resources.orders.*'))
                    ->url(fn(): string => route('filament.admin.resources.orders.index')),
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarFullyCollapsibleOnDesktop()
            ->maxContentWidth('full');
    }
}
