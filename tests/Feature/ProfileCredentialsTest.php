<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('profile settings update username and password for the next login', function () {
    $user = User::factory()->create([
        'name' => 'Profile Test User',
        'username' => 'old-login-name',
        'password' => Hash::make('current-password'),
        'role' => 'faculty',
    ]);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Sign-in username')
        ->assertSee('Change password');

    $this->put(route('profile.username.update'), [
        'username' => 'new-login-name',
        'current_password' => 'current-password',
    ])->assertSessionHasNoErrors();

    $this->put(route('profile.password.update'), [
        'current_password' => 'current-password',
        'password' => 'replacement-password-2026',
        'password_confirmation' => 'replacement-password-2026',
    ])->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'password-updated');

    $user->refresh();

    expect($user->username)->toBe('new-login-name')
        ->and(Hash::check('replacement-password-2026', $user->password))->toBeTrue();

    $this->post(route('faculty.logout'));

    $this->post(route('login.submit'), [
        'username' => 'new-login-name',
        'password' => 'replacement-password-2026',
    ])->assertRedirect(route('faculty.dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('profile rejects an incorrect current password when changing the username', function () {
    $user = User::factory()->create([
        'username' => 'unchanged-login-name',
        'password' => Hash::make('current-password'),
        'role' => 'faculty',
    ]);

    $this->actingAs($user)
        ->put(route('profile.username.update'), [
            'username' => 'attempted-login-name',
            'current_password' => 'incorrect-password',
        ])
        ->assertSessionHasErrorsIn('updateUsername', 'current_password');

    expect($user->fresh()->username)->toBe('unchanged-login-name');
});
