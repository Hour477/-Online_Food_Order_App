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
        @case('role')
            shield_person
            @break

        @case('settings')
            settings
            @break
        @case('image')
            image
            @break
        @case('table')
            dining
            @break
        @case('chart')
            show_chart
            @break
        @case('payment')
            attach_money
            @break
        @case('customer')
            person
            @break
        @case('banner')
            images
            @break
        @default
            article
    @endswitch
</span>