<x-guest-layout>
    <h1 class="font-editorial text-[40px] font-thin mb-10 ">Already a member?<br> Access your Panda Portal here.</h1>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

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

        <!-- Remember Me -->
        <div class="flex mt-8 justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm " name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="underline-animated text-sm text-gray-600 " href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-start mt-4">
            <x-panda-form-submit class="mt-4 ">
                {{ __('Login') }}
            </x-panda-form-submit>
    
        </div>
        <div class="h-[200px]"></div>
        <div class="lg:flex">
            <p class="my-10 lg:px-3 lg:my-0">Not registered yet?<br> No problem, it’s quick and easy to set up a Panda account.</p>
            <x-btn-white  :link="route('register')">Register</x-btn-white>
        </div>
    </form>
</x-guest-layout>
