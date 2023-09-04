<section class="pb-[min(184px,25.5vw)] max-w-[1920px] mx-auto grid grid-cols-1 lg:grid-cols-2">
    <div class="space-y-4 px-[min(6.99vw,50px)] py-4 order-2 lg:order-1">
        <div>
            <img class="object-cover" src="{{ asset('storage/assets/pandas-black-grey.png') }}" alt="Panda images" class="" />
        </div>
        <div class="flex justify-center">
            <h1 class="font-editorial text-6xl font-thin">Live. Grow. <br> Play. <br> Panda.</h1>
        </div>
    </div>
    <div class="px-[min(6.99vw,50px)] max-w-xl mx-auto py-4 order-1 lg:order-2 flex flex-col justify-between">
        <div class="space-y-4">
            <h1 class="font-editorial text-4xl font-thin mb-10">
                @if (empty(auth()->user()->email_verified_at))
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                @elseif (empty(auth()->user()->organization_verified_at))
                    {{ __('Your account is under verification.') }}
                @endif
            </h1>
            <div>
                @if (empty(auth()->user()->email_verified_at))
                    <x-button outlined size="lg" wire:click="resend">
                        {{ __('Resend Verification Email') }}
                    </x-button>
                @endif
            </div>
        </div>
    </div>
</section>
