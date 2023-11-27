@php
    $tabs = [
        'Daily Deals' => 'resources.user-resource.widgets.list-daily-deals',
        'My Benefits' => 'resources.user-resource.widgets.list-benefits',
        'My Details' => 'resources.user-resource.forms.edit-profile-form',
        'My Preferences' => 'resources.user-resource.forms.edit-preferences-form',
        'My Orders' => 'resources.user-resource.widgets.list-orders',
    ];
@endphp

<section x-data="{ selected: @js(request('activeTab', 0)) }" class="py-8 lg:px-[min(6.99vw,50px)]">
    <div class="max-w-[1920px] mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-4 ">
            <ul class="divide-y text-xl border-y">
                @foreach ($tabs as $key => $menu)
                    <li class="space-y-6">
                        <div class="p-4">
                            <button x-bind:class="{ 'font-bold': selected == {{ $loop->index }} }" x-on:click="selected = {{ $loop->index }}">
                                {{ $key }}
                            </button>
                        </div>
                        <div x-cloak x-bind:class="{ 'block lg:hidden': selected == {{ $loop->index }}, 'hidden': selected != {{ $loop->index }} }">
                            <livewire:is :component="$menu" wire:key="{{ $loop->index }}" />
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="col-span-3 hidden lg:block">
                @foreach ($tabs as $key => $menu)
                    <div class="hidden h-full" x-bind:class="{ 'lg:grid lg:grid-cols-1': selected == {{ $loop->index }} }">
                        <div class="place-self-center w-full">
                            <livewire:is :component="$menu" wire:key="{{ $menu }}" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
