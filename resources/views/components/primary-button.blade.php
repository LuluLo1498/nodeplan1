<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-verdes border border-transparent rounded-full font-semibold text-xs text-verdes-dark uppercase tracking-widest hover:bg-verdes-light focus:bg-verdes-dark active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
