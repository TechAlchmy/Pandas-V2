<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium" x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <div class="flex">
            <span class="uppercase select-none caret-transparent">
                {{ $getPlaceholder() }}
            </span>
            <span>{{ $isRequired() ? '*' : '' }}</span>
        </div>

        <x-input
            :attributes="\Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                ->merge($getExtraAlpineAttributes(), escape: false)
                ->merge([
                    'autocapitalize' => $getAutocapitalize(),
                    'autocomplete' => $getAutocomplete(),
                    'autofocus' => $isAutofocused(),
                    'disabled' => $isDisabled(),
                    'required' => $isRequired(),
                    'type' => $getType() ?? 'text',
                    $applyStateBindingModifiers('wire:model') => $getStatePath(),
                ], escape: false)
                "
        />
    </div>
</x-dynamic-component>
