<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('resources.subscriber-resource.forms.create-subscriber-form');

    $component->assertSee('');
});
