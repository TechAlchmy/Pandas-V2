<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('resources.user-resource.widgets.list-daily-deals');

    $component->assertSee('');
});
