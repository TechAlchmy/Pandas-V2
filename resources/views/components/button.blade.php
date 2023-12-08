@props([
    'tag' => 'button',
    'outlined' => false,
    'size' => 'md',
    'color' => 'black',
    'action' => null,
    'unstyled' => false,
    'buttonClasses' => null,
])

@php
    $buttonClasses = \Illuminate\Support\Arr::toCssClasses([
        $buttonClasses,
        'inline-block text-center border leading-6 rounded-[70%] capitalize transition',
        ...match ($color) {
            'white' => ['text-white hover:bg-white hover:text-black'],
            default => ['text-gray-900'],
        },
        ...match ($size) {
            'lg' => ['px-16 py-4 text-lg min-w-[236px]'],
            default => ['px-14 py-3 text-base'],
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

@if ($unstyled)
    <button {{ $attributes }}>
        {{ $slot }}
    </button>
@elseif ($tag == 'button')
    <button {{ $attributes->when($action, function ($attributes, $action) {
            return $attributes->merge(['x-on:click' => $action->getAlpineClickHandler(), 'wire:click' => $action->getLivewireClickHandler(), 'wire:target' => $action->getLivewireTarget()]);
        })->twMerge([$buttonClasses]) }}>
        {{ $slot }}
    </button>
@elseif ($tag == 'a')
    <x-a {{ $attributes->twMerge([$buttonClasses]) }}>
        {{ $slot }}
    </x-a>
@endif
