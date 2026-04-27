<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-verdes border border-verdes-dark rounded-full font-semibold text-xs text-verdes-dark uppercase tracking-widest shadow-sm hover:bg-verdes-light focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
