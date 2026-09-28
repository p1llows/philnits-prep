<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-block px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
