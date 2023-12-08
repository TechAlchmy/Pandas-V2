<div class="p-4 lg:pt-0 lg:pb-0">
    <div x-show="!$wire.isEditing" class="grid grid-cols-1 lg:grid-cols-4 gap-6 border-t pt-4">
        <div>
            <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
            <button class="underline" x-on:click="$wire.$toggle('isEditing')">Edit</button>
        </div>
        <div class="space-y-2">
            <div>
                <label class="text-neutral-400">Employer:</label>
                <p>{{ auth()->user()->organization?->name ?? '-' }}</p>
            </div>
            <div>
                <label class="text-neutral-400">Email:</label>
                <p>{{ auth()->user()->email ?? '-' }}</p>
            </div>
        </div>
        <div class="space-y-2">
            <div>
                <label class="text-neutral-400">Phone:</label>
                <p>{{ auth()->user()->phone ?? '-' }}</p>
            </div>
            <div>
                <label class="text-neutral-400">Address:</label>
                <p>{{ auth()->user()->address ?? '-' }}</p>
            </div>
        </div>
        <div>
            {{ auth()->user()->organization?->getFirstMedia('logo')?->img() }}
        </div>
    </div>
    <form wire:submit.prevent="save" x-show="$wire.isEditing">
        {{ $this->form }}
        <div class="flex justify-end">
            <x-button type="submit" size="lg" outlined class="inline-block mt-8 hover:bg-panda-green hover:border-transparent">
                Update
            </x-button>
        </div>
    </form>
</div>
