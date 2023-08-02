<x-guest-layout>
    <h1 class="font-editorial text-[40px] font-thin mb-10 ">Panda People</h1>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mt-7 relative">
            <x-input-label for="email" :value="__('Email*')" />
            <x-text-input id="email" class="block mt-1 w-full panda-text-input-email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

         <x-panda-form-submit class="mt-10 ">
            {{ __('Send Password Reset') }}
        </x-panda-form-submit>
    </form>
</x-guest-layout>
