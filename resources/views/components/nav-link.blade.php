@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 panda-nav-active '
            : 'inline-flex items-center px-1 pt-1  panda-nav transition duration-150 ease-in-out hover:panda-nav';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
