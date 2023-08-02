<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        
        <div class="relative">
            <x-input-label for="email" :value="__('Email*')" />
            <x-text-input id="email" class="block mt-1 w-full panda-text-input-email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-7 relative">
            <x-input-label for="password" :value="__('Password*')" />
            <x-text-input id="password" class="block mt-1 w-full panda-text-input-pwd" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-7 relative">
            <x-input-label for="password_confirmation" :value="__('Confirm Password*')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full panda-text-input-pwdrep"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-start mt-4">
            <x-panda-form-submit class="">
                {{ __('Reset Password') }}
            </x-panda-form-submit>
    
        </div>
    </form>
</x-guest-layout>
