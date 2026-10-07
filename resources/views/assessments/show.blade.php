<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">
            {{ __('Take assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface rounded-xl border border-line p-5 md:p-6">
                <livewire:assessment-interface :assessment-id="$assessment->id" />
            </div>
        </div>
    </div>
</x-app-layout>
