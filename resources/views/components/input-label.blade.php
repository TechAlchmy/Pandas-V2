@props(['value'])

<label {{ $attributes->merge(['class' => 'absolute uppercase pandas-label noselect nocaret nodrag font-medium font-aeonik']) }}>
    {{ $value ?? $slot }}
</label>
