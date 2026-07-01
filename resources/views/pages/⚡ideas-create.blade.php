<?php

use Livewire\Component;

new class extends Component {
    public $title = '';
    public $description = '';
    public $status = 'pending';
    public $image_path = null;
    public $steps = [];
    public $step = '';
    public $resources = [];
    public $resource = '';

    public function create()
    {
        $this->validate();
        dd($this->all());
    }

    public function addStep()
    {
        if (trim($this->step)) {
            $this->steps[] = $this->step;
            $this->reset('step');
        }
    }

    public function deleteStep($value) {
        $this->steps = collect($this->steps)->reject(fn($item) => $item === $value)->values()->all();
        dd($this->steps);
        

    }

    public function rules()
    {
        return [
            'title' => ['required', 'min:3', 'max:250'],
            'description' => ['required', 'min:3', 'max:1000'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'image_path' => ['nullable', 'image', 'max:10'],
            'steps' => ['nullable', 'array'],
            'steps.*' => ['string', 'min:3', 'max:5120'],
            'resources' => ['nullable', 'array'],
            'resources.*' => ['url'],
        ];
    }
};
?>

<div>
    <div class="p-6 max-w-3xl mx-auto space-y-8">

        <!-- HEADER BAR -->
        <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-800">
            <div>
                <flux:heading size="xl">Light Up a New Idea</flux:heading>
                <p class="text-sm text-zinc-500 mt-1 dark:text-zinc-400">Fill out the details below to kickstart your
                    next project roadmap.</p>
            </div>
            <flux:button href="#" variant="ghost" icon="x-mark" size="sm">Cancel</flux:button>
        </div>

        <!-- MAIN FORM BODY -->
        <form class="space-y-6" wire:submit="create">

            <!-- 1. TITLE INPUT -->
            <flux:input wire:model="title" label="Idea Title" placeholder="e.g., Build a Mobile SaaS App..."
                description="Keep it short, clear, and action-oriented." />

            <!-- 2. DESCRIPTION TEXTAREA -->
            <flux:textarea wire:model="description" label="Description & Scope"
                placeholder="Describe what this idea is, who it is for, and why it's worth pursuing..."
                rows="5" />

            <!-- 3. STATUS SELECT -->
            <flux:select wire:model="status" label="Initial Status" placeholder="Choose a starting phase...">
                <flux:select.option value="pending">⏳ Pending (Backlog)</flux:select.option>
                <flux:select.option value="in_progress">🚀 In Progress (Active)</flux:select.option>
                <flux:select.option value="completed">✓ Completed (Done)</flux:select.option>
            </flux:select>

            <!-- 4. IMAGE UPLOAD -->
            <flux:input wire:model="image_path" type="file" label="Preview Image Media"
                description="Upload an inspiring thumbnail or design mockup (PNG, JPG)." />

            <flux:separator class="my-8" />

            <!-- 5. DYNAMIC STEPS BUILDER PLACEHOLDER -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="lg">Action Steps</flux:heading>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Break your idea down into small, digestible
                            roadmap milestones.</p>
                    </div>

                </div>
                <div class="flex justify-around gap-2 items-center">
                    <flux:input wire:model="step" placeholder="Add step"></flux:input>
                    <flux:button wire:click="addStep" variant="ghost" icon="plus" size="sm">Add Step
                    </flux:button>
                </div>

                <!-- Steps Static Placeholder Items List -->
                @if (count($steps))
                    <div
                        class="space-y-3 bg-zinc-50/50 p-4 rounded-xl border border-zinc-200 dark:bg-zinc-900/40 dark:border-zinc-800">
                        <!-- Example Row 1 -->
                        @foreach ($steps as $step)
                            <div class="flex items-center gap-3">
                                <div class="text-xs font-semibold text-zinc-400 w-5">{{ $loop->iteration }}</div>
                                <div class="flex-1">
                                    <p>{{ $step }}</p>
                                </div>
                                <flux:button wire:click="deleteStep({{ $step }})" icon="trash" variant="ghost" color="red" size="sm" inset />
                            </div>
                        @endforeach

                    </div>

                @endif
            </div>

            <flux:separator class="my-8" />

            <!-- 6. RESOURCES BUILDER PLACEHOLDER -->
            <div class="space-y-4">

                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="lg">Helpful Resources</flux:heading>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Link inspiring links, technical docs,
                            repositories, or assets.</p>
                    </div>
                    <flux:button variant="ghost" icon="plus" size="sm">Add Resource</flux:button>
                </div>

                <!-- Resources Static Placeholder Rows Grid -->
                <div
                    class="space-y-3 bg-zinc-50/50 p-4 rounded-xl border border-zinc-200 dark:bg-zinc-900/40 dark:border-zinc-800">
                    div
                    <div class="">
                        <flux:input placeholder="URL Link (e.g., https://github.com/...)" size="sm"
                            icon="link" />
                    </div>
                </div>
            </div>

            <!-- FORM SUBMISSION ACTIONS -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-zinc-200 dark:border-zinc-800">

                <flux:button variant="primary" color="green" type="submit">Create Idea</flux:button>
            </div>

        </form>
    </div>
</div>
