<div
    {{
        $attributes
            ->except(['wire:target'])
            ->class([
                'border-b border-black flex rounded-lg shadow-sm ring-1 transition duration-75',
            ])
    }}
>
    <div
        @class([
            'min-w-0 flex-1',
        ])
    >
        {{ $slot }}
    </div>
</div>