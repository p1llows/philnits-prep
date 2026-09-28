<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Topics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Review by Topic</h1>
                <p class="text-gray-600">
                    Browse examination questions organized by topic to strengthen your understanding in specific areas.
                </p>
            </div>

            <!-- Topics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($topics as $topic)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                        <!-- Color Bar -->
                        <div class="h-2" style="background-color: {{ $topic['color'] }}"></div>
                        
                        <div class="p-6">
                            <!-- Topic Info -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $topic['name'] }}</h3>
                                    @if($topic['code'])
                                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                                            {{ $topic['code'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            @if($topic['description'])
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ $topic['description'] }}
                                </p>
                            @endif

                            <!-- Question Count -->
                            <div class="mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ number_format($topic['published_questions']) }}</span>
                                    <span class="text-gray-500 ml-1">questions available</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="space-y-2">
                                <a 
                                    href="{{ route('topics.show', $topic['id']) }}"
                                    class="block w-full text-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium"
                                >
                                    Review Questions
                                </a>
                                
                                <a 
                                    href="{{ route('topics.practice', $topic['id']) }}"
                                    class="block w-full text-center px-4 py-2 border-2 border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors font-medium"
                                >
                                    Practice This Topic →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if(count($topics) === 0)
                <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No topics available</h3>
                    <p class="mt-1 text-sm text-gray-500">Topics will appear here once content is published.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
