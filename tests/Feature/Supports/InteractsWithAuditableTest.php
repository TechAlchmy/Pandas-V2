<?php

namespace Tests\Feature\Supports;

use App\Concerns\InteractsWithAuditable;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

it('can audit when doing create, update, delete on a model with auditable traits', function () {
    $user = User::factory()->create();
    actingAs($user);
    $auditableModel = Brand::create([
        'name' => 'test',
        'link' => 'test',
        'slug' => 'test',
        'uniqid' => 'test',
    ]);

    assertTrue($auditableModel->createdBy->is($user));
    assertNull($auditableModel->updatedBy);

    actingAs($user2 = User::factory()->create());
    $auditableModel->update(['name' => 'test2']);
    $auditableModel->load('updatedBy');
    assertTrue($auditableModel->updatedBy->is($user2));
    assertTrue($auditableModel->createdBy->is($user));

    actingAs($user3 = User::factory()->create());
    $auditableModel->delete();
    $auditableModel->load('deletedBy');
    assertTrue($auditableModel->trashed());
    assertTrue($auditableModel->deletedBy->is($user3));
    assertTrue($auditableModel->updatedBy->is($user2));
    assertTrue($auditableModel->createdBy->is($user));
});
