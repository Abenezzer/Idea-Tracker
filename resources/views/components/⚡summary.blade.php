<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\User;

new class extends Component {
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    #[Computed]
    public function summary()
    {
        
        return [
            'all' => $this->user->ideas()->count(),
            'pending' => $this->user->ideas()->where('status', 'pending')->count(),
            'in_progress' => $this->user->ideas()->where('status', 'in_progress')->count(),
            'completed' => $this->user->ideas()->where('status', 'completed')->count(),
        ];
    }

    public function placeholder()
    {
        
        return view('components.placeholder');
    }

};
?>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
    <flux:card class="space-y-1">
        <span class="text-sm font-medium text-zinc-500">Total Ideas</span>
        <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $this->summary['all'] }}</div>
    </flux:card>

    <flux:card class="space-y-1">
        <span class="text-sm font-medium text-amber-600">Pending</span>
        <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $this->summary['pending'] }}</div>
    </flux:card>

    <flux:card class="space-y-1">
        <span class="text-sm font-medium text-blue-600">In Progress</span>
        <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $this->summary['in_progress'] }}</div>
    </flux:card>

    <flux:card class="space-y-1">
        <span class="text-sm font-medium text-emerald-600">Completed</span>
        <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $this->summary['completed'] }}</div>
    </flux:card>
</div>
