@props([
    'tag' => 'button',
    'action' => null,
])
@if ($tag == 'button')
    <button
        {{ $attributes->when($action, function ($attributes, $action) {
                return $attributes->merge(['x-on:click' => $action->getAlpineClickHandler(), 'wire:click' => $action->getLivewireClickHandler(), 'wire:target' => $action->getLivewireTarget()]);
            })->twMerge(['underline-animated text-gray-600']) }}>
        {{ $slot }}
    </button>
@elseif ($tag == 'a')
    <x-a {{ $attributes->twMerge([$buttonClasses]) }}>
        {{ $slot }}
    </x-a>
@endif
