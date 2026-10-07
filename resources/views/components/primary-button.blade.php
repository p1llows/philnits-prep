<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-ink text-paper font-medium text-sm rounded-lg hover:bg-black focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition duration-150 ease-in-out']) }}>
    {{ $slot }}
</button>

