<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="border-b-[1.5px] py-2 border-black items-center font-medium" x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <span class="uppercase select-none caret-transparent flex">{{ $getPlaceholder() }} <span>{{ $isRequired() ? '*' : '' }}</span></span>
        <textarea
            wire:model="{{ $getStatePath() }}"
            rows="{{ $getRows() }}"
            {{
                $attributes
                    ->class(['block w-full border-none bg-white p-0 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 disabled:bg-gray-50 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:bg-white/5 dark:text-white dark:placeholder:text-gray-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 focus:ring-0'])
            }}
        >
        </textarea>
    </div>
</x-dynamic-component>
