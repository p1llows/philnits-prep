<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-surface rounded-xl border border-line">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block font-medium text-sm text-stone">Name</label>
                            <input id="name" type="text" class="mt-1 block w-full rounded-lg border-line text-ink shadow-sm focus:border-accent focus:ring-accent" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mt-4">
                            <label for="email" class="block font-medium text-sm text-stone">Email</label>
                            <input id="email" type="email" class="mt-1 block w-full rounded-lg border-line text-ink shadow-sm focus:border-accent focus:ring-accent" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <button type="submit" class="px-4 py-2 bg-ink text-surface rounded-lg hover:bg-stone text-sm font-medium">Save changes</button>
                            
                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" class="text-sm text-stone">Saved.</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
