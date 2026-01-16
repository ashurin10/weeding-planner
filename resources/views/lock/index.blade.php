<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('lock.unlock') }}">
        @csrf

        <!-- PIN -->
        <div>
            <x-input-label for="pin" :value="__('Enter App PIN')" />
            <x-text-input id="pin" class="block mt-1 w-full" type="password" name="pin" required autofocus />
            <x-input-error :messages="$errors->get('pin')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Unlock') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>