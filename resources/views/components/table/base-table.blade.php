<div class="w-full overflow-x-auto rounded-[10px] border border-gray-200 dark:border-gray-700">
    {{-- TABLE horizontal --}}
    <table {{ $attributes->merge(['class' => 'min-w-[600px] w-full lg:min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left rtl:text-right' ]) }}>
        
        {{-- HEADER --}}
        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 whitespace-nowrap">
            {{ $head }}
        </thead>

        {{-- BODY --}}
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 whitespace-nowrap">
            {{ $body }}
        </tbody>

    </table>
</div>