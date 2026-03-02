<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-stone-800">Welcome back</h2>
        <p class="text-stone-500 text-sm mt-1">Sign in to your admin account</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded border-stone-300 text-amber-600 shadow-sm focus:ring-amber-400" name="remember">
                <span class="text-sm text-stone-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>

        <p class="mt-5 text-center text-sm text-stone-500">
            Don't have an account?
            <a href="{{ route('register') }}"
                class="font-semibold text-amber-600 hover:text-amber-700 transition-colors">
                Register
            </a>
        </p>
    </form>
</x-guest-layout>