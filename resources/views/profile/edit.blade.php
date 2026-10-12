<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                    <span>ACCOUNT SETTINGS</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">Profile information</h1>
                <p class="text-sm text-stone">Update your account's profile name and email address.</p>
            </div>

            <div class="p-6 sm:p-8 bg-surface rounded-2xl border border-[#CFCABD] shadow-xs">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block font-medium text-xs font-mono text-stone uppercase tracking-wider mb-1.5">Full Name</label>
                            <input id="name" type="text" class="block w-full rounded-xl border-[#D9D5C9] text-ink shadow-xs focus:border-accent focus:ring-accent text-sm" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div>
                            <label for="email" class="block font-medium text-xs font-mono text-stone uppercase tracking-wider mb-1.5">Email Address</label>
                            <input id="email" type="email" class="block w-full rounded-xl border-[#D9D5C9] text-ink shadow-xs focus:border-accent focus:ring-accent text-sm" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 text-sm font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                Save changes
                            </button>
                            
                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-mono text-correct font-semibold">
                                    ✓ Profile updated successfully.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
