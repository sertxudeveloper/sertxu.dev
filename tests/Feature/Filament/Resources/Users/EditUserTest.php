<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.users.edit', ['record' => $this->user->id]))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.users.edit', ['record' => $this->user->id]))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(EditUser::class, [
        'record' => $this->user->id,
    ])
        ->assertSuccessful();
});

it('can load user data into the form', function () {
    $this->actingAs($this->admin);

    livewire(EditUser::class, [
        'record' => $this->user->id,
    ])
        ->assertSchemaStateSet([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'is_admin' => $this->user->is_admin,
        ]);
});

it('can update a user', function () {
    $this->actingAs($this->admin);

    $newUserData = User::factory()->make();

    livewire(EditUser::class, [
        'record' => $this->user->id,
    ])
        ->fillForm([
            'name' => $newUserData->name,
            'email' => $newUserData->email,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(User::class, [
        'id' => $this->user->id,
        'name' => $newUserData->name,
        'email' => $newUserData->email,
    ]);
});

it('can update a user to be an admin', function () {
    $this->actingAs($this->admin);

    livewire(EditUser::class, [
        'record' => $this->user->id,
    ])
        ->fillForm([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'is_admin' => true,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(User::class, [
        'id' => $this->user->id,
        'is_admin' => true,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $newUserData = User::factory()->make();

    livewire(EditUser::class, [
        'record' => $this->user->id,
    ])
        ->fillForm([
            'name' => $newUserData->name,
            'email' => $newUserData->email,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`email` is a valid email address' => [['email' => 'not-an-email'], ['email' => 'email']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
]);

it('can delete a user', function () {
    $this->actingAs($this->admin);

    $user = User::factory()->create();

    livewire(EditUser::class, [
        'record' => $user->id,
    ])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing($user);
});
