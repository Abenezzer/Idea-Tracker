<?php

use App\Models\User;
use Livewire\Livewire;

test('registration page renders successfully', function () {
    $this->get('/register')
        ->assertStatus(200);
});

test('a new user can register through the single-file component', function () {
    // 1. Double-check that our database starts empty
    expect(User::count())->toBe(0);

    // 2. Simulate filling out the fields and hitting submit
    Livewire::test('pages::register')
        ->set('name', 'Abenezer Daniel')
        ->set('email', 'abenezer@example.com')
        ->set('password', 'securepassword123')
        ->set('password_confirmation', 'securepassword123')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect('/'); // Matches your redirect path

    // 3. Verify database state
    expect(User::count())->toBe(1);
    
    $this->assertDatabaseHas('users', [
        'name' => 'Abenezer Daniel',
        'email' => 'abenezer@example.com',
    ]);

    // 4. Verify that the user was automatically authenticated
    $this->assertAuthenticated();
});

test('registration validation catches invalid fields', function () {
    Livewire::test('pages::register')
        ->set('name', '') // Required field is empty
        ->set('email', 'not-an-email') // Invalid email string
        ->set('password', '123') // Too short (min:6)
        ->set('password_confirmation', 'mismatch-456') // Doesn't match
        ->call('register')
        ->assertHasErrors([
            'name' => 'required',
            'email' => 'email',
            'password' => 'confirmed'
        ]);

    $this->assertGuest();
});

test('registration requires a unique email address', function () {
    // Create an existing user first
    User::factory()->create([
        'email' => 'duplicate@example.com',
    ]);

    // Attempt to sign up with that exact same email
    Livewire::test('pages::register')
        ->set('name', 'Another User')
        ->set('email', 'duplicate@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertHasErrors(['email' => 'unique']);
});