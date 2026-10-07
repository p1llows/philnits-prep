<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Mistakes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Review Your Mistakes</h1>
                <p class="text-gray-600">
                    Learn from your errors by practicing questions you answered incorrectly. Keep reviewing until you get them right!
                </p>
            </div>

            <!-- Mistake Review Livewire Component -->
            <livewire:mistake-review />

        </div>
    </div>
</x-app-layout>
