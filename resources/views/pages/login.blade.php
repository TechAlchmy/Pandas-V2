<?php
use function Laravel\Folio\{name, middleware};

middleware(['guest']);
name('login');
?>
@php
    $forEmployer = session('url.intended') == route('employer') || request('from') == 'employer';
@endphp
<x-layouts.guest class="bg-panda-green" :for-employer="$forEmployer">
    <section class="pb-[min(184px,25.5vw)] max-w-[1920px] grid grid-cols-1 lg:grid-cols-2 mx-auto">
        <div class="space-y-4 px-[min(6.99vw,50px)] py-4 order-2 lg:order-1">
            <div>
                <img class="object-cover" src="{{ getMediaPath('assets/pandas-black-grey.png') }}" alt="Panda images" class="" />
            </div>
            <div class="flex justify-center">
                <h1 class="text-6xl font-thin font-editorial">Live. Grow. <br> Play. <br> Panda.</h1>
            </div>
        </div>
        <div class="px-[min(6.99vw,50px)] max-w-xl mx-auto py-4 order-1 lg:order-2 flex flex-col justify-between">
            <div class="space-y-6">
                <h1 class="mb-10 text-5xl font-thin font-editorial">Already a member?<br> Access your Panda<br />Portal here.</h1>
                <livewire:resources.auth-resource.forms.login-form />
                <div class="items-center gap-2 lg:flex">
                    <p class="my-10">Not registered yet?<br> No problem, it’s quick and easy to set up a Panda account.</p>
                    <div>
                        <x-link class="hover:bg-black hover:text-white" href="/register?from={{ $forEmployer ? 'employer' : '' }}" outlined>Register</x-link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.guest>
