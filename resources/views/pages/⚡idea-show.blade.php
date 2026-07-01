<?php

use Livewire\Component;
use App\Models\Idea;

new class extends Component {
    public Idea $idea;
};
?>

<div class="p-6 max-w-7xl mx-auto space-y-8">
    <nav>
        <flux:navbar class="border-b border-zinc-200 pb-4 dark:border-zinc-800">
            <flux:brand href="/" logo="💡" name="IdeaTracker" class="text-xl font-bold tracking-tight" />
            <flux:spacer />

            <flux:profile href="/" name="{{ $idea->user->name }}" initials="{{ substr($idea->user->name, 0, 2) }}"
                class="cursor-pointer" />
            <flux:button href="/logout" variant="danger">Logout</flux:button>
        </flux:navbar>
    </nav>

    <div class="p-6 max-w-4xl mx-auto space-y-8">


        <!-- 1. HEADER CONTROL LAYER -->
        <div class="flex items-center justify-between">
            <flux:button icon="chevron-left" variant="ghost" size="sm">Back to Ideas</flux:button>
            <div class="flex items-center space-x-2">
                <flux:button variant="ghost" icon="pencil-square" size="sm">Edit Idea</flux:button>
                <flux:button variant="ghost" icon="trash" color="red" size="sm">Delete</flux:button>
            </div>
        </div>

        <!-- 2. MAIN IDEA CONTENT -->
        <flux:card class="p-0 overflow-hidden space-y-6">
            <!-- Hero Preview Image -->
            <div class="aspect-21/9 w-full bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-800">
                @if (!$idea->image_path)
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1200" alt="Idea Banner"
                        class="h-full w-full object-cover">
                @endif
            </div>

            <!-- Meta Titles Section -->
            <div class="px-6 pb-6 space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <flux:heading size="xl">{{ $idea->title }}</flux:heading>
                    <flux:badge color="amber">{{ $idea->status }}</flux:badge>
                </div>

                <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    {{ $idea->description }}
                </p>
            </div>
        </flux:card>

        <!-- 3. ASSOCIATED STEPS WORKFLOW SECTION -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <flux:heading size="lg">Project Steps & Roadmap</flux:heading>
                <flux:button variant="filled" size="sm" icon="plus">Add Step</flux:button>
            </div>

            <!-- Steps Stack Container -->
            <div class="space-y-3">
                @if ($idea->steps)

                    @foreach ($idea->steps as $step)
                        <div
                            class="flex items-start p-4 bg-white rounded-xl border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800 gap-4">
                            <div
                                class="mt-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/40">
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">✓</span>
                            </div>
                            <div class="flex-1 space-y-1">
                                <h4 class="font-medium text-sm text-zinc-500 line-through dark:text-zinc-400">Initial
                                    Market Research & Wireframing</h4>

                            </div>
                            <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" inset />
                        </div>
                    @endforeach

                @endif

            </div>
        </div>
    </div>
</div>
