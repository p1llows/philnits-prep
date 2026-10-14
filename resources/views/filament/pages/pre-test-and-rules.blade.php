<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center justify-end gap-x-3 pt-4 border-t border-[#E3DFD5] dark:border-gray-800">
            <x-filament::button type="submit" size="md">
                Save changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
