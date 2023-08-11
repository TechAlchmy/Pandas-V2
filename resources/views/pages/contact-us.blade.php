<x-layouts.app>
    <x-topbar />
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div>
            <div class="relative min-h-screen"
                style="background-image: url(https://images.unsplash.com/photo-1648832328633-89b993c5d6b7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=987&q=80)">
                <div class="absolute inset-0 p-8 text-white flex flex-col justify-between">
                    <h1 class="text-6xl">
                        Both of our ears are open.
                    </h1>
                    <p>
                        Questions? Suggestions? They’re all welcome.

                        We’re here to make your Panda Portal as helpful as possible and are always looking for ways to make daily living easier. Like a panda leisurely seeking out its roots, shoots, and leaves for lunch.

                        That said, we love prompt responses and won’t be leisurely about getting back to you.
                    </p>
                </div>
            </div>
        </div>
        <div class="p-8 space-y-4">
            <h2 class="text-6xl font-light">Contact Us</h2>
            <livewire:resources.contact-inquiry-resource.forms.contact-us-form />
        </div>
    </div>
    <x-footer />
</x-layouts.app>
