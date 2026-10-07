@props(['user'])

<nav x-data="{ open: false }" class="bg-surface border-b border-line">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <span class="font-semibold text-xl text-accent">{{ config('app.name', 'PhilNITS Prep') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if(Auth::check())
                        <a href="{{ route('dashboard') }}" 
                           class="{{ request()->routeIs('dashboard') ? 'inline-flex items-center px-1 pt-1 border-b-2 border-accent text-sm font-medium leading-5 text-ink' : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-stone hover:text-ink hover:border-line' }}">
                            {{ __('Dashboard') }}
                        </a>

                        <a href="{{ route('topics.index') }}" 
                           class="{{ request()->routeIs('topics.*', 'practice.*', 'mistakes.*') ? 'inline-flex items-center px-1 pt-1 border-b-2 border-accent text-sm font-medium leading-5 text-ink' : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-stone hover:text-ink hover:border-line' }}">
                            {{ __('Practice & Review') }}
                        </a>

                        <a href="{{ route('assessments.index') }}" 
                           class="{{ request()->routeIs('assessments.*') ? 'inline-flex items-center px-1 pt-1 border-b-2 border-accent text-sm font-medium leading-5 text-ink' : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-stone hover:text-ink hover:border-line' }}">
                            {{ __('Assessments') }}
                        </a>

                        <a href="{{ route('analytics.index') }}" 
                           class="{{ request()->routeIs('analytics.*') ? 'inline-flex items-center px-1 pt-1 border-b-2 border-accent text-sm font-medium leading-5 text-ink' : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-stone hover:text-ink hover:border-line' }}">
                            {{ __('Analytics') }}
                        </a>

                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @if(Auth::check())
                    <x-dropdown>
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-line text-sm leading-4 font-medium rounded-md text-stone bg-surface hover:text-ink hover:bg-paper focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="ml-3">
                        <a href="{{ route('login') }}" class="text-stone hover:text-ink text-sm font-medium">
                            Login
                        </a>
                    </div>
                    <div class="ml-3">
                        <a href="{{ route('register') }}" class="text-stone hover:text-ink text-sm font-medium">
                            Register
                        </a>
                    </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-stone hover:text-ink hover:bg-paper focus:outline-none focus:bg-paper transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-line bg-surface">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::check())
                <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('dashboard') ? 'border-accent text-accent bg-accent-tint' : 'border-transparent text-stone hover:text-ink hover:bg-paper hover:border-line' }}">
                    {{ __('Dashboard') }}
                </a>
    
                <a href="{{ route('topics.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('topics.*', 'practice.*', 'mistakes.*') ? 'border-accent text-accent bg-accent-tint' : 'border-transparent text-stone hover:text-ink hover:bg-paper hover:border-line' }}">
                    {{ __('Practice & Review') }}
                </a>
    
                <a href="{{ route('assessments.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('assessments.*') ? 'border-accent text-accent bg-accent-tint' : 'border-transparent text-stone hover:text-ink hover:bg-paper hover:border-line' }}">
                    {{ __('Assessments') }}
                </a>
    
                <a href="{{ route('analytics.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('analytics.*') ? 'border-accent text-accent bg-accent-tint' : 'border-transparent text-stone hover:text-ink hover:bg-paper hover:border-line' }}">
                    {{ __('Analytics') }}
                </a>
                
            @endif
        </div>

        @if(Auth::check())
            <div class="pt-4 pb-3 border-t border-line">
                <div class="px-4">
                    <div class="font-medium text-base text-ink">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-stone">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-stone hover:text-ink hover:bg-paper">
                        {{ __('Profile') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           class="block px-4 py-2 text-sm text-stone hover:text-ink hover:bg-paper"
                           onclick="event.preventDefault();
                                       this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </a>
                    </form>
                </div>
            </div>
        @endif
    </div>
</nav>
