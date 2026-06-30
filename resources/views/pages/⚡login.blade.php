<?php

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $email;
    public $password;
    public $loginMessage;

    public function login()
    {
        $this->validate();

        if (!Auth::attempt($this->only(['email', 'password']))) {
            $this->loginMessage = 'Invalid Email or Password';
            return;
        }

        $this->redirectIntended('/', navigate: true);
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'max:1000'],
            'password' => ['required'],
        ];
    }
};
?>

<div class="min-h-screen flex flex-col justify-center py-2 sm:px-6 lg:px-8 bg-zinc-50 dark:bg-zinc-900">
    <div class="sm:mx-auto w-full max-w-md">
        <!-- Logo / App Name -->
        <div class="text-center">
            <h2 class=" text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Login
            </h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Or
                <a href="/register" wire:navigate
                    class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                    Signup If you don't have an account
                </a>
            </p>
        </div>

        <!-- Card Container -->
        <div class="mt-8 sm:mx-auto w-full max-w-md">
            <div
                class="bg-white dark:bg-zinc-800 py-8 px-4 shadow sm:rounded-xl sm:px-10 border border-zinc-200 dark:border-zinc-700">

                <!-- Flux Form Layout -->
                <form wire:submit="login" class="space-y-2">

                    <!-- Email Address -->
                    <flux:input wire:model="email" label="Email Address" type="email" placeholder="you@example.com"
                        required />

                    <!-- Password -->
                    <flux:input wire:model="password" label="Password" type="password" placeholder="••••••••"
                        required />



                    <!-- Submit Button -->
                    <div class="pt-2">
                        <flux:button type="submit" variant="filled"
                            class="w-full justify-center bg-indigo-600! hover:bg-indigo-700! text-white!">
                            Login
                        </flux:button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
