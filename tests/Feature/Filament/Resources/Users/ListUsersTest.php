<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.users.index'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.users.index'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(ListUsers::class)
        ->assertSuccessful();
});

it('can list users', function () {
    $this->actingAs($this->admin);

    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('can search users by name', function () {
    $this->actingAs($this->admin);

    $users = User::factory()->count(5)->create();

    $searchTerm = $users->first()->name;

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($users->where('name', $searchTerm));
});

it('can search users by email', function () {
    $this->actingAs($this->admin);

    $users = User::factory()->count(5)->create();

    $searchTerm = $users->last()->email;

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($users->where('email', $searchTerm));
});

it('can sort users by name', function () {
    $this->actingAs($this->admin);

    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->sortTable('name')
        ->assertCanSeeTableRecords($users->sortBy('name'), inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($users->sortByDesc('name'), inOrder: true);
});

it('can sort users by email', function () {
    $this->actingAs($this->admin);

    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->sortTable('email')
        ->assertCanSeeTableRecords($users->sortBy('email'), inOrder: true)
        ->sortTable('email', 'desc')
        ->assertCanSeeTableRecords($users->sortByDesc('email'), inOrder: true);
});

it('can sort users by is_admin', function () {
    $this->actingAs($this->admin);

    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->sortTable('is_admin')
        ->assertCanSeeTableRecords($users);
});

it('can sort users by created_at', function () {
    $this->actingAs($this->admin);

    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($users);
});

it('can bulk delete users', function () {
    $this->actingAs($this->admin);

    $users = User::factory()->count(3)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->selectTableRecords($users)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified()
        ->assertCanNotSeeTableRecords($users);

    $users->each(fn (User $user) => assertDatabaseMissing($user));
});
