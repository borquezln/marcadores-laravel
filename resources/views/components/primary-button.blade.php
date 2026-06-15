<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-[#3d3d3d] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-[#4d4d4d] focus:bg-gray-700 dark:focus:bg-[#4d4d4d] active:bg-gray-900 dark:active:bg-[#282828] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-[#282828] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
