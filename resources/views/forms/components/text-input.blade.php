<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="p-2 border-b border-black flex gap-x-2 items-center" x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <span>{{ $getPlaceholder() }}</span>
        <x-input x-model="state" />
    </div>
</x-dynamic-component>
