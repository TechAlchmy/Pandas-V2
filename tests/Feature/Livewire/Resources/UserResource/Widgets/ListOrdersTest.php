<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('resources.user-resource.widgets.list-orders');

    $component->assertSee('');
});
