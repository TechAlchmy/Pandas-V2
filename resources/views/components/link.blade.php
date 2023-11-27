@props([
    'tag' => 'a',
    'outlined' => false,
    'size' => 'md',
    'color' => 'black',
])

@php
    $buttonClasses = \Illuminate\Support\Arr::toCssClasses([
        'inline-block text-center border leading-6 rounded-[70%]',
        ...match ($color) {
            'white' => ['text-white'],
            default => ['text-gray-900'],
        },
        ...match ($size) {
            'lg' => ['px-16 py-4 text-lg min-w-[236px]'],
            default => ['px-10 py-3 text-base'],
        },
        ...$outlined
            ? [
                'border-2' => $size == 'lg',
                ...match ($color) {
                    'white' => ['border-white'],
                    default => ['border-black'],
                },
            ]
            : [
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
    <x-a {{ $attributes->twMerge([$buttonClasses]) }}>
        {{ $slot }}
    </x-a>
@endif
