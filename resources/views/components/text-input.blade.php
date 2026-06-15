@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-neutral-600 dark:bg-[#323232] dark:text-gray-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
