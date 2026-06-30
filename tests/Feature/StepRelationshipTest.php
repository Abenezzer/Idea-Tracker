<?php

use App\Models\User;
use App\Models\Idea;
use App\Models\Step;

test('an idea can have zero or multiple steps, and a step belongs to one idea', function () {
    // 1. Setup: Create a base user and a parent idea
    $user = User::factory()->create();
    
    $idea = Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    // 2. Test Case A: Validate the "0 steps" boundary condition
    expect($idea->steps)->toHaveCount(0);

    // 3. Setup: Create 3 individual steps linked to this specific idea
    $steps = Step::factory()->count(3)->create([
        'idea_id' => $idea->id,
    ]);

    // Refresh the model instance to pull down fresh structural relation data
    $idea->refresh();

    // 4. Test Case B: Validate Idea -> Steps (HasMany) relationship
    expect($idea->steps)->toHaveCount(3);
    expect($idea->steps->first())->toBeInstanceOf(Step::class);

    // 5. Test Case C: Validate Step -> Idea (BelongsTo) relationship
    $singleStep = $steps->first();
    expect($singleStep->idea)->toBeInstanceOf(Idea::class);
    expect($singleStep->idea->id)->toBe($idea->id);
});