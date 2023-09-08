<div class="max-w-[1920px] mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div>
            <div class="relative min-h-[10rem] md:h-full w-full"
                style="background-image: url(https://images.unsplash.com/photo-1648832328633-89b993c5d6b7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=987&q=80)">
                <div x-data="@js(['index' => 0, 'testimonials' => $this->testimonials])" x-init="setInterval(() => {
                    if (index + 1 >= testimonials.length) {
                        index = 0;
                    } else {
                        index++;
                    }
                }, 5000)" class="absolute inset-0 p-6 text-white space-y-4">
                    <h3 class="text-4xl lg:text-6xl font-editorial" x-transition x-text="testimonials[index][1]"></h3>
                    <p x-text="testimonials[index][0]" x-transition>
                    </p>
                </div>
            </div>
        </div>
        <div class="p-8 space-y-4 bg-panda-green">
            <h2 class="text-6xl font-light font-editorial">Contact Us</h2>
            <form wire:submit.prevent="create">
                {{ $this->form }}
                <div class="flex justify-end">
                    <x-button outlined class="inline-block mt-8">
                        Submit
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
