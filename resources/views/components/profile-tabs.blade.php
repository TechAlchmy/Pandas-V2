@php
    $tabs = [
        'Daily Deals' => 'resources.user-resource.widgets.list-daily-deals',
        'My Benefits' => 'resources.user-resource.widgets.list-benefits',
        'My Details' => 'resources.user-resource.forms.edit-profile-form',
        'My Preferences' => 'resources.user-resource.forms.edit-preferences-form',
        'My Orders' => 'resources.user-resource.widgets.list-orders',
    ];
@endphp

<section x-data="{ selected: @js(request('activeTab', 0)) }" class="grid grid-cols-1 lg:grid-cols-4 py-8 px-[min(6.99vw,50px)]">
    <ul class="divide-y text-xl">
        @foreach ($tabs as $key => $menu)
            <li class="p-4">
                <div>
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
            <div class="hidden" x-bind:class="{ 'lg:block': selected == {{ $loop->index }} }">
                <livewire:is :component="$menu" wire:key="{{ $menu }}" />
            </div>
        @endforeach
    </div>
</section>
