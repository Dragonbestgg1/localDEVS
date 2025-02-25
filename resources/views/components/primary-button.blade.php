<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-white uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-gray-500  dark:focus:bg-gray-700 focus:bg-white dark:active:bg-gray-900 active:bg-gray-300 focus:outline-none focus:ring-2 dark:focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
