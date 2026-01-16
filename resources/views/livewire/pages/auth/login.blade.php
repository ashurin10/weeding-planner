<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="login">
        <!-- PIN Input -->
        <div class="mt-4">
            <x-input-label for="pin" :value="__('Enter PIN')" />

            <x-text-input wire:model="form.pin" id="pin" class="block mt-1 w-full text-center text-4xl tracking-[1em]"
                type="password" name="pin" required autofocus autocomplete="current-password" maxlength="6" />

            <x-input-error :messages="$errors->get('form.pin')" class="mt-2 text-center" />
        </div>

        <div class="flex items-center justify-center mt-8">
            <x-primary-button class="w-full justify-center py-3 text-lg">
                {{ __('Open Planner') }}
            </x-primary-button>
        </div>
    </form>
</div>