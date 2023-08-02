<x-guest-layout>
    <h1 class="font-editorial text-[40px] font-thin mb-10 ">Panda People</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="relative">
            <x-input-label for="name" :value="__('Name*')" />
            <x-text-input id="name" class="block mt-1 w-full panda-text-input-email" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- <div class="mt-4 relative">
            <x-input-label for="" :value="__('*')" />
            <x-text-input id="" class="block mt-1 w-full panda-text-input-" type="" name="" :value="old('')" required autofocus autocomplete="" />
            <x-input-error :messages="$errors->get('')" class="mt-2" />
        </div> --}}
        

        <!-- Email Address -->
        <div class="mt-7 relative">
            <x-input-label for="email" :value="__('Email*')" />
            <x-text-input id="email" class="block mt-1 w-full panda-text-input-email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-7 relative">
            <x-input-label for="password" :value="__('Password*')" />

            <x-text-input id="password" class="block mt-1 w-full panda-text-input-pwd"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

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

        

        <div class="flex mt-8 justify-end">
            @if (Route::has('register'))
                <a class="underline-animated text-sm text-gray-600 " href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-start mt-4">
            <x-panda-form-submit class="">
                {{ __('Register') }}
            </x-panda-form-submit>
    
        </div>
    </form>
</x-guest-layout>
