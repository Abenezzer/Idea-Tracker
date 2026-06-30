<?php

use App\Models\User;
use Livewire\Livewire;


test('a user can log in successfully with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'developer@example.com',
        'password' => bcrypt('secret-password'),
    ]);

    Livewire::test('pages::login') // 🌟 Uses Livewire::test instead of Volt
        ->set('email', 'developer@example.com')
        ->set('password', 'secret-password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
});

test('a user cannot log in with invalid credentials and sets the loginMessage property', function () {
    User::factory()->create([
        'email' => 'developer@example.com',
        'password' => bcrypt('correct-password'),
    ]);

    Livewire::test('pages::login')
        ->set('email', 'developer@example.com')
        ->set('password', 'wrong-password')
        ->call('login')
        // 🌟 Verifies the public property update seamlessly
        ->assertSet('loginMessage', 'Invalid Email or Password');

    $this->assertGuest();
});