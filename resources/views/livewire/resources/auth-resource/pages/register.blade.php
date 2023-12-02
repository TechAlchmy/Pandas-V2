<section class="pb-[min(184px,25.5vw)] max-w-[1920px] mx-auto grid grid-cols-1 lg:grid-cols-2">
    <div class="space-y-4 px-[min(6.99vw,50px)] py-4 order-2 lg:order-1">
        <div>
            <img class="object-cover" src="{{ getMediaPath('assets/pandas-black-grey.png') }}" alt="Panda images" class="" />
        </div>
        <div class="flex justify-center">
            <h1 class="text-6xl font-thin font-editorial">Live. Grow. <br> Play. <br> Panda.</h1>
        </div>
    </div>
    <div class="px-[min(6.99vw,50px)] max-w-xl mx-auto py-4 order-1 lg:order-2 flex flex-col justify-between">
        <div class="space-y-4">
            <h1 class="mb-10 text-4xl font-thin font-editorial">Welcome to Panda</h1>
            <h3>{{ $this->organization?->name }}</h3>
            <div>
                <form wire:submit.prevent="register">
                    {{ $this->form }}
                    <x-button type="submit" size="lg" outlined class="inline-block mt-8 hover:bg-black hover:text-white">
                        Register
                    </x-button>
                </form>
            </div>
            <div class="items-center gap-2 lg:flex">
                <p class="my-10">Already registered?<br> Sign in now.</p>
                <div>
                    <x-link href="/login?from={{ $fromEmployer ? 'employer' : '' }}" outlined size="lg" class="hover:bg-black hover:text-white">Sign in</x-link>
                </div>
            </div>
        </div>
    </div>
</section>
