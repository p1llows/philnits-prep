@props(['user'])

<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-paper/90 backdrop-blur-md border-b border-[#E3DFD5] transition-all duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-8">
                <!-- Logo Matching Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 text-accent font-medium text-base shrink-0 group" aria-label="Passy Dashboard">
                        <x-mascot variant="avatar" :size="32" class="w-8 h-8 rounded-full shrink-0 shadow-xs transition-transform duration-200 group-hover:scale-105" />
                        <span class="font-extrabold text-accent text-xl tracking-tight">Passy</span>
                    </a>
                </div>

                <!-- Clean Nav Links matching Design System -->
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8 text-sm font-medium">
                    @if(Auth::check())
                        <a href="{{ route('dashboard') }}" 
                           class="transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-accent font-semibold' : 'text-stone hover:text-ink' }}">
                            {{ __('Dashboard') }}
                        </a>

                        <a href="{{ route('topics.index') }}" 
                           class="transition-colors duration-200 {{ request()->routeIs('topics.*', 'practice.*', 'mistakes.*') ? 'text-accent font-semibold' : 'text-stone hover:text-ink' }}">
                            {{ __('Study') }}
                        </a>

                        <a href="{{ route('assessments.index') }}" 
                           class="transition-colors duration-200 {{ request()->routeIs('assessment*', 'assessments*') ? 'text-accent font-semibold' : 'text-stone hover:text-ink' }}">
                            {{ __('Assessments') }}
                        </a>

                        <a href="{{ route('analytics.index') }}" 
                           class="transition-colors duration-200 {{ request()->routeIs('analytics.*') ? 'text-accent font-semibold' : 'text-stone hover:text-ink' }}">
                            {{ __('Progress & Analytics') }}
                        </a>

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" 
                               class="transition-colors duration-200 {{ request()->routeIs('admin.*') ? 'text-accent font-semibold' : 'text-stone hover:text-ink' }}">
                                {{ __('Admin') }}
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center">
                @if(Auth::check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="relative flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 hover:opacity-90 transition duration-150 p-0.5 border border-[#D9D5C9] bg-surface">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-8 h-8 rounded-full bg-accent text-surface font-semibold flex items-center justify-center text-xs overflow-hidden">
                                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <span style="display:none;">{{ Auth::user()->initials }}</span>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-[#E3DFD5] bg-panel">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-accent text-surface font-semibold flex items-center justify-center text-xs overflow-hidden shrink-0">
                                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <span style="display:none;">{{ Auth::user()->initials }}</span>
                                    </div>
                                    <div class="truncate min-w-0">
                                        <p class="text-sm font-semibold text-ink truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-stone truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')" class="text-xs font-medium">
                                {{ __('Profile Settings') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="text-xs font-medium text-wrong hover:text-wrong"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-2 text-xs font-medium">
                        <a href="{{ route('login') }}" class="text-stone hover:text-ink px-3 py-2">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="bg-accent text-surface hover:bg-accent/90 rounded-xl px-4 py-2 font-medium shadow-xs">
                            Get Started
                        </a>
                    </div>
                @endif
            </div>

            <!-- Hamburger for Mobile -->
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-stone hover:text-ink hover:bg-surface border border-[#E3DFD5] focus:outline-none transition duration-150" aria-label="Toggle Navigation">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-[#E3DFD5] bg-paper px-4 py-3 space-y-2">
        @if(Auth::check())
            <a href="{{ route('dashboard') }}" class="block px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-surface text-accent shadow-xs font-semibold' : 'text-stone hover:text-ink hover:bg-surface' }}">
                {{ __('Dashboard') }}
            </a>

            <a href="{{ route('topics.index') }}" class="block px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('topics.*', 'practice.*', 'mistakes.*') ? 'bg-surface text-accent shadow-xs font-semibold' : 'text-stone hover:text-ink hover:bg-surface' }}">
                {{ __('Study') }}
            </a>

            <a href="{{ route('assessments.index') }}" class="block px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('assessment*', 'assessments*') ? 'bg-surface text-accent shadow-xs font-semibold' : 'text-stone hover:text-ink hover:bg-surface' }}">
                {{ __('Assessments') }}
            </a>

            <a href="{{ route('analytics.index') }}" class="block px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('analytics.*') ? 'bg-surface text-accent shadow-xs font-semibold' : 'text-stone hover:text-ink hover:bg-surface' }}">
                {{ __('Progress & Analytics') }}
            </a>

            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.*') ? 'bg-accent text-surface shadow-xs' : 'text-accent hover:bg-surface' }}">
                    {{ __('Admin Area') }}
                </a>
            @endif

            <div class="pt-3 border-t border-[#E3DFD5]">
                <div class="flex items-center px-2 py-1 mb-2">
                    <div class="w-8 h-8 rounded-full bg-accent text-surface font-semibold flex items-center justify-center text-xs overflow-hidden shrink-0 mr-3">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">{{ Auth::user()->initials }}</span>
                    </div>
                    <div class="truncate min-w-0">
                        <div class="font-semibold text-sm text-ink truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-stone truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="block px-3.5 py-2 rounded-xl text-sm text-stone hover:text-ink hover:bg-surface">
                    {{ __('Profile Settings') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       class="block px-3.5 py-2 rounded-xl text-sm text-wrong hover:bg-wrong-surface"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        @endif
    </div>
</nav>
