<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium" x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <span class="uppercase select-none caret-transparent flex">{{ $getPlaceholder() }} <span>{{ $isRequired() ? '*' : '' }}</span></span>
        <x-input x-model="state" :disabled="$isDisabled()" />
    </div>
</x-dynamic-component>
