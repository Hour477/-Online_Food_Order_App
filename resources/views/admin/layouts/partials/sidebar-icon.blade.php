@php
    $icon = $icon ?? 'circle';
    $class = $class ?? 'w-5 h-5 flex-shrink-0';
@endphp

<span class="material-symbols-outlined {{ $class }}" aria-hidden="true">
    @switch($icon)
        @case('home')
            dashboard
            @break
        @case('users')
            group
            @break
        @case('tag')
            label
            @break
        @case('menu')
            menu_book
            @break
        @case('table')
            table_chart
            @break
        @case('chart')
            show_chart
            @break
        @case('card')
            credit_card
            @break
        @case('customer')
            person
            @break
        @default
            article
    @endswitch
</span>