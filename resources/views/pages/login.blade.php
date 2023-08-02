<!DOCTYPE html>
<html lang="en">
<head>
    <x-header>
    </x-header>
    <style>
        
    </style>
</head>
<body class="panda-green-bg">
    <nav class="w-full flex flex-col panda-green-bg sticky top-0 z-30 ">
        <div class="w-full flex flex-row justify-between px-[min(6.99vw,50px)] lg:pl-12 h-[75px] lg:h-[100px] items-center mx-auto" style="max-width:1920px"><a href="/"><x-application-logo  decoding="async" data-nimg="1" class="max-h-[28.5px] lg:max-h-[43px]" loading="eager" style="color:transparent"/></a>
        </div>
    </nav>
    <section class="w-full pb-[min(184px,25.5vw)] hidden lg:flex flex-col">
        <div class="flex mx-auto" style="max-width: 1920px;">
            <div class="w-1/2 flex flex-col justify-center relative">
                <div>
                    <img src="{{ asset('storage/assets/pandas-black-grey.png') }}" alt="Panda images" class=""/>
                </div>
                <div class="bottom-out">
                    <h1 class="font-editorial text-[60px] font-thin">Live. Grow. <br> Play. <br> Panda.</h1>
                </div>
            </div>
            <div class="w-1/2 flex p-11 flex-col justify-center max-w-[600px]">
                <h1 class="font-editorial text-[40px] font-thin mb-10">Already a member?<br> Access your Panda Portal here.</h1>
                <form action="">
                    <div class="relative">
                        <x-input-label for="email" :value="__('Email*')" />
                        <x-text-input id="email" class="block mt-1 w-full panda-text-input-email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <div class="mt-4 relative">
                        <x-input-label for="password" :value="__('Password*')" />
                        <x-text-input id="password" class="block mt-1 w-full panda-text-input-pwd" type="password" name="password" required autocomplete="current-password" />
                    </div>

                    <div class="flex mt-8 justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm " name="remember">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="  text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md " href="{{ route('password.request') }}">
                                {{ __('Forgot your passwordd?? yy') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-start mt-4">
                        <x-panda-form-submit class="mt-4 ">
                            {{ __('Login') }}
                        </x-panda-form-submit>
                    </div>
                </form>
                <div class="h-[200px]"></div>
                <div class="flex">
                    <p>Not registered yet?<br> No problem, it’s quick and easy to set up a Panda account.</p>
                    <x-btn-white  :link="route('profile.edit')">Register</x-btn-white>
                </div>
            </div>
        </div>
    </section>

    <section class=" lg:hidden w-full pb-[min(184px,25.5vw)] ">
        <div class=" mx-auto flex-col" style="max-width: 1920px;">
            <div class="w-full flex p-11 flex-col justify-center max-w-[600px] m-auto">
                <h1 class="font-editorial text-[40px] font-thin mb-10 ">Already a member?<br> Access your Panda Portal here.</h1>
                <form action="">
                    <div class="relative">
                        <x-input-label for="email" :value="__('Email*')" />
                        <x-text-input id="email" class="block mt-1 w-full panda-text-input-email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <div class="mt-4 relative">
                        <x-input-label for="password" :value="__('Password*')" />
                        <x-text-input id="password" class="block mt-1 w-full panda-text-input-pwd" type="password" name="password" required autocomplete="current-password" />
                    </div>

                    <div class="flex mt-8 justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="underline-animated  text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                {{ __('Forgot your passwordd? y') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-start mt-4">
                        <x-panda-form-submit class="mt-4 ">
                            {{ __('Login') }}
                        </x-panda-form-submit>
                   
                    </div>
                </form>
                
                <p class= "my-10">Not registered yet?<br> No problem, it’s quick and easy to set up a Panda account.</p>
                <x-btn-white  :link="route('profile.edit')">Register</x-btn-white>
                
            </div>
            <div class="w-full flex flex-col justify-center relative">
                <div>
                    <img src="{{ asset('storage/assets/pandas-black-grey.png') }}" alt="Panda images" class=""/>
                </div>
                <div class="bottom-out">
                    <h1 class="font-editorial text-[min(80px,9.2vw)] font-thin">Live. Grow. <br> Play. <br class="hidden lg:block"> Panda.</h1>
                </div>
            </div>
            
        </div>
    </section>
    <x-footer/>
</body>
</html>
