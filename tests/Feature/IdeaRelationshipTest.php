<?php

use App\Models\User;
use App\Models\Idea;

test('a user can have many ideas and an idea belongs to a user', function () {
    // 1. Arrange: Create a user and 3 ideas assigned to them
    $user = User::factory()->create();
    
    $ideas = Idea::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    // 2. Act & Assert: Check the One-to-Many connection from both sides
    
    // Test User -> Ideas (HasMany)
    expect($user->ideas)->toHaveCount(3);
    expect($user->ideas->first())->toBeInstanceOf(Idea::class);

    // Test Idea -> User (BelongsTo)
    $singleIdea = $ideas->first();
    expect($singleIdea->user)->toBeInstanceOf(User::class);
    expect($singleIdea->user->id)->toBe($user->id);
});

test('a user can only see and access their own ideas', function () {
    // 1. Arrange: Create two separate users
    $currentUser = User::factory()->create();
    $otherUser = User::factory()->create();

    // Create an idea for our current user
    $myIdea = Idea::factory()->create([
        'user_id' => $currentUser->id,
        'title' => 'My Secret Project',
    ]);

    // Create an idea for the other user
    $strangerIdea = Idea::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Someone Elses Project',
    ]);

    // 2. Act: Authenticate the current user and scope the ideas query
    $this->actingAs($currentUser);

    // We fetch ideas through the authenticated user's relationship scope
    $accessibleIdeas = auth()->user()->ideas()->get();

    // 3. Assert: Verify the isolation boundary works flawlessly
    expect($accessibleIdeas)->toHaveCount(1);
    expect($accessibleIdeas->first()->id)->toBe($myIdea->id);
    expect($accessibleIdeas->pluck('id'))->not->toContain($strangerIdea->id);
});