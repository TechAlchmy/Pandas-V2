<x-guest-layout>
    <h1 class="font-editorial text-[40px] font-thin mb-10 ">Panda People</h1>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

       <!-- Password -->
        <div class="mt-7 relative">
            <x-input-label for="password" :value="__('Password*')" />
            <x-text-input id="password" class="block mt-1 w-full panda-text-input-pwd" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

       

        <div class="flex items-center justify-start mt-4">
            <x-panda-form-submit class="">
                {{ __('Confrim') }}
            </x-panda-form-submit>
    
        </div>
    </form>
</x-guest-layout>
