<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\User;
use App\IdeaStatus;

new class extends Component {
    use WithPagination;

    public ?User $user;
    public $statusColor = [];
    public $filterValue;
    public $statuses = [];
    public $searchTerm = '';

    public function mount()
    {
        $this->user = Auth::user();
        $this->statusColor = [
            'pending' => 'red',
            'in_progress' => 'yellow',
            'completed' => 'green',
        ];

        $this->statuses = collect(IdeaStatus::cases())->map(fn($case) => $case->value)->toArray();
        $this->statuses[] = 'all';
        $this->filterValue = 'all';
    }

    #[Computed]
    public function summary()
    {
        sleep(5);
        return [
            'all' => $this->user->ideas()->count(),
            'pending' => $this->user->ideas()->where('status', 'pending')->count(),
            'in_progress' => $this->user->ideas()->where('status', 'in_progress')->count(),
            'completed' => $this->user->ideas()->where('status', 'completed')->count(),
        ];
    }

    public function filter($value)
    {
        if (!in_array($value, $this->statuses)) {
            abort(403, 'Invalid Input');
        }
        $this->filterValue = $value;
        $this->resetPage();
    }

    #[Computed]
    public function ideas()
    {
        if (!trim($this->searchTerm)) {
            return $this->user
                ->ideas()
                ->when($this->filterValue !== 'all', function ($query) {
                    $query->where('status', $this->filterValue);
                })
                ->paginate(10);
        }
        return $this->user
            ->ideas()
            ->when($this->filterValue !== 'all', function ($query) {
                $query->where('status', $this->filterValue);
            })
            ->where('title', 'LIKE', '%' . $this->searchTerm . '%')
            ->paginate(10);
    }
};
?>

<div class="p-6 max-w-7xl mx-auto space-y-8">

    <!-- 1. NAVIGATION BAR -->
     <nav>
        <flux:navbar class="border-b border-zinc-200 pb-4 dark:border-zinc-800">
        <flux:brand href="/" logo="💡" name="IdeaTracker" class="text-xl font-bold tracking-tight" />
        <flux:spacer />

        <flux:profile href="/" name="{{ $user->name }}" initials="{{ substr($user->name, 0, 2) }}"
            class="cursor-pointer" />
        <flux:button href="/logout" variant="danger">Logout</flux:button>
    </flux:navbar>
    </nav>

    <!-- 2. STATUS STATS CARDS -->
    <livewire:summary :user="$user" lazy />





    <!-- 3. CONTROLS (SEARCH & STATUS FILTER BAR) -->
    <div
        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-zinc-50 p-4 rounded-xl dark:bg-zinc-900/50">
        <!-- Flux Search Input -->
        <div class="w-full sm:max-w-xs">
            <flux:input wire:model.live.debounce="searchTerm" placeholder="Search ideas..." icon="magnifying-glass" />
        </div>

        <!-- Flux Button Group / Filter Links -->
        <div class="flex flex-wrap gap-2">
            <flux:button wire:click="filter('all')" variant="{{ $filterValue == 'all' ? 'primary' : 'ghost' }}"
                size="sm">All</flux:button>
            <flux:button wire:click="filter('pending')" wire:click="filter('cool')"
                variant="{{ $filterValue == 'pending' ? 'primary' : 'ghost' }}" size="sm">Pending</flux:button>
            <flux:button wire:click="filter('in_progress')"
                variant="{{ $filterValue == 'in_progress' ? 'primary' : 'ghost' }}" size="sm">In Progress
            </flux:button>
            <flux:button wire:click="filter('completed')"
                variant="{{ $filterValue == 'completed' ? 'primary' : 'ghost' }}" size="sm">Completed</flux:button>
        </div>
    </div>
    <div>
        <flux:button href="/ideas/create" wire:navigate class="cursor-pointer bg-blue-500! text-white! border-0! hover:translate-y-1 hover:pointer">Create A new Idea +</flux:button>
    </div>

    <!-- 4. IDEAS GRID CARDS -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:transition>

        @foreach ($this->ideas as $idea)
            <a class="block" href="/ideas/{{ $idea->id }}" wire:navigate>
                <flux:card class="p-0 overflow-hidden group cursor-pointer transition hover:shadow-md">
                    <div
                        class="aspect-video w-full bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-800">
                        @if (!$idea->image_path)
                            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=500"
                                alt="Placeholder" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between gap-4">
                            <flux:heading size="lg" class="line-clamp-1">{{ $idea->title }}</flux:heading>
                            <flux:badge color="{{ $statusColor[$idea->status->value] }}">{{ $idea->status }}
                            </flux:badge>
                        </div>
                        <p class="text-sm text-zinc-500 line-clamp-2 dark:text-zinc-400">
                            {{ $idea->description }}
                        </p>
                    </div>
                </flux:card>
            </a>
        @endforeach



    </div>
    <div>
        <flux:pagination :paginator="$this->ideas" />
    </div>
</div>
