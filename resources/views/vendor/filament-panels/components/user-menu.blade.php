@php
    $user = filament()->auth()->user();
@endphp

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_BEFORE) }}

<x-filament::dropdown
    placement="bottom-end"
    teleport
    :attributes="
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-user-menu'])
    "
>
    <x-slot name="trigger">
        <button
            aria-label="{{ __('filament-panels::layout.actions.open_user_menu.label') }}"
            type="button"
            class="relative flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-[#1F3A5F] focus:ring-offset-2 hover:opacity-90 transition duration-150 p-0.5 border border-[#D9D5C9] bg-white shrink-0"
        >
            <x-filament-panels::avatar.user :user="$user" class="w-8 h-8 rounded-full" />
        </button>
    </x-slot>

    <div class="w-56 overflow-hidden">
        {{-- User info header --}}
        <div class="px-4 py-3 border-b border-[#E3DFD5] bg-[#FDFCFA]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#1F3A5F] text-white font-semibold flex items-center justify-center text-xs overflow-hidden shrink-0">
                    <x-filament-panels::avatar.user :user="$user" class="w-full h-full object-cover" />
                </div>
                <div class="truncate min-w-0">
                    <p class="text-sm font-bold text-[#1F3A5F] truncate leading-tight">{{ filament()->getUserName($user) }}</p>
                    <p class="text-xs text-[#8C827A] truncate leading-tight mt-0.5">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        {{-- Menu items --}}
        <div class="py-1">
            <a
                href="{{ route('filament.admin.auth.profile') }}"
                class="flex items-center w-full px-4 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-[#1F3A5F] transition-colors"
            >
                {{ __('Profile Settings') }}
            </a>

            <form method="POST" action="{{ filament()->getLogoutUrl() }}">
                @csrf
                <button
                    type="submit"
                    class="flex items-center w-full text-left px-4 py-2.5 text-xs font-medium text-[#B91C1C] hover:bg-red-50 hover:text-[#991B1B] transition-colors"
                >
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-filament::dropdown>

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_AFTER) }}
