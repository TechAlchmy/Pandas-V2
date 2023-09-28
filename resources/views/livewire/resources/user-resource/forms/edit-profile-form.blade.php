<div class="p-4 lg:p-8">
    <form wire:submit.prevent="save">
    <div x-show="!$wire.isEditing" class="grid grid-cols-1 lg:grid-cols-4 gap-6 border-t pt-4">
        <div>
            <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
        </div>
        <div class="space-y-2">
            <div>
                <label class="text-gray-600">Employer:</label>
                <p>{{ auth()->user()->organization?->name ?? '-' }}</p>
            </div>
            <div>
                <label class="text-gray-600">Email:</label>
                <p>{{ auth()->user()->email ?? '-' }}</p>
            </div>
        </div>
        <div class="space-y-2">
            <div>
                <label class="text-gray-600">Phone:</label>
                <p>{{ auth()->user()->phone ?? '-' }}</p>
            </div>
            <div>
                <label class="text-gray-600">Address:</label>
                <p>{{ auth()->user()->address ?? '-' }}</p>
            </div>
        </div>
        <div>
            {{ auth()->user()->organization?->getFirstMedia('logo')?->img() }}
        </div>
    </div>
        {{ $this->form }}
        <div class="flex justify-end">
            <x-button type="submit" size="lg" outlined class="inline-block mt-8">
                Update
            </x-button>
        </div>
    </form>
</div>
