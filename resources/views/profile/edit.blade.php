@extends('layouts.admin')

@section('title', 'Profile')
@section('heading', 'Profile')

@section('content')
    <div class="max-w-3xl space-y-6">

        {{-- ── Profile Information ──────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-stone-100">
                <h2 class="font-bold text-stone-800">Profile Information</h2>
                <p class="text-sm text-stone-400 mt-0.5">Update your name and email address.</p>
            </div>
            <div class="px-8 py-6">
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-semibold text-stone-700 mb-1.5">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                            autocomplete="name" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm text-stone-800
                                      focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                      @error('name') border-red-400 bg-red-50 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-stone-700 mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                            autocomplete="username" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm text-stone-800
                                      focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                      @error('email') border-red-400 bg-red-50 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                            <p class="mt-2 text-xs text-stone-500">
                                Your email is unverified.
                                <button form="send-verification"
                                    class="text-amber-600 hover:text-amber-700 underline font-semibold">
                                    Resend verification email
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-1 text-xs text-emerald-600 font-semibold">Verification link sent!</p>
                            @endif
                        @endif
                    </div>

                    <div class="flex items-center gap-4 pt-1">
                        <button type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-colors shadow-sm">
                            Save Changes
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                                class="text-sm text-emerald-600 font-semibold">
                                Saved!
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Update Password ──────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-stone-100">
                <h2 class="font-bold text-stone-800">Update Password</h2>
                <p class="text-sm text-stone-400 mt-0.5">Use a long, random password to keep your account secure.</p>
            </div>
            <div class="px-8 py-6">
                <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    <div>
                        <label for="update_password_current_password"
                            class="block text-sm font-semibold text-stone-700 mb-1.5">Current Password</label>
                        <input id="update_password_current_password" name="current_password" type="password"
                            autocomplete="current-password" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                      @error('current_password', 'updatePassword') border-red-400 bg-red-50 @enderror">
                        @error('current_password', 'updatePassword')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block text-sm font-semibold text-stone-700 mb-1.5">New
                            Password</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                            class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                      @error('password', 'updatePassword') border-red-400 bg-red-50 @enderror">
                        @error('password', 'updatePassword')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation"
                            class="block text-sm font-semibold text-stone-700 mb-1.5">Confirm Password</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password"
                            class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                    </div>

                    <div class="flex items-center gap-4 pt-1">
                        <button type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-colors shadow-sm">
                            Update Password
                        </button>
                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                                class="text-sm text-emerald-600 font-semibold">
                                Password updated!
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Delete Account ───────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-red-200 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-red-100">
                <h2 class="font-bold text-red-700">Delete Account</h2>
                <p class="text-sm text-stone-400 mt-0.5">Permanently delete your account and all associated data.</p>
            </div>
            <div class="px-8 py-6">
                <p class="text-sm text-stone-600 mb-5">
                    Once your account is deleted, all of its resources and data will be permanently removed.
                    Please download any data you wish to retain before proceeding.
                </p>

                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-colors shadow-sm">
                    Delete Account
                </button>
            </div>
        </div>

    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-stone-800">Are you sure?</h3>
                    <p class="text-sm text-stone-400">This action cannot be undone.</p>
                </div>
            </div>

            <p class="text-sm text-stone-600 mb-5">
                Enter your password to confirm you want to permanently delete your account.
            </p>

            <div class="mb-5">
                <label for="delete_password" class="block text-sm font-semibold text-stone-700 mb-1.5">Password</label>
                <input id="delete_password" name="password" type="password" placeholder="Enter your password"
                    class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent">
                @error('password', 'userDeletion')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-sm font-semibold text-stone-600 hover:text-stone-800 px-5 py-2.5 rounded-xl border border-stone-200 hover:border-stone-300 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="text-sm font-bold bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl transition-colors">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
@endsection