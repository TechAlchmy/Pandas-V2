@props([
    'tag' => 'button',
    'outlined' => false,
    'size' => 'md',
    'color' => 'black',
])

@php
    $buttonClasses = \Illuminate\Support\Arr::toCssClasses([
        'border leading-6 rounded-[70%]',
        ... match ($color) {
            'white' => ['text-white'],
            default => ['text-gray-900'],
        },
        ...match ($size) {
            'lg' => ['px-16 py-6 text-lg'],
            default => ['px-8 py-3 text-base'],
        },
        ...$outlined ? 
            [
                'border-2' => $size == 'lg',
                ...match ($color) {
                    'white' => ['border-white'],
                    default => ['border-black'],
                },
            ] : 
            [
                'border-transparent',
                ...match ($color) {
                    'white' => ['hover:border-white'],
                    default => ['hover:border-black'],
                },
            ],
    ]);
@endphp

@if ($tag == 'button')
    <button {{ $attributes->twMerge([$buttonClasses]) }}>
        {{ $slot }}
    </button>
@endif
@if ($tag == 'a')
    <x-link {{ $attributes->twMerge([$buttonClasses]) }}>
        {{ $slot }}
    </x-link>
@endif
