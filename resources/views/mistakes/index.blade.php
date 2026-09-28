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
                
                @if($this->getUnresolvedCount() > 0)
                    <div class="mt-3 flex items-center space-x-4 text-sm">
                        <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full">
                            <span class="font-bold mr-1">{{ $this->getUnresolvedCount() }}</span> unresolved
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full">
                            <span class="font-bold mr-1">{{ $this->getResolvedCount() }}</span> resolved
                        </span>
                    </div>
                @endif
            </div>

            <!-- Error State - No Mistakes -->
            @if($this->getTotalMistakes() === 0)
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <svg class="mx-auto h-16 w-16 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">No mistakes to review!</h2>
                    <p class="text-gray-600 mb-6">You've mastered all the questions you've practiced so far. Great job!</p>
                    <a href="{{ route('topics.index') }}" class="inline-block px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Browse Topics
                    </a>
                </div>

            @else
                <!-- Mistake Review Component -->
                <livewire:mistake-review />

            @endif
        </div>
    </div>
</x-app-layout>
