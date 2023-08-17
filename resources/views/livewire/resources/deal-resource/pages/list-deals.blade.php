<div>
    <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto lg:flex lg:justify-between lg:gap-6">
        <h1 class="text-2xl lg:text-4xl text-center lg:text-start mb-8 lg:mb-0">
            Live More with Panda-Powered Deals + Discounts
        </h1>
        <div>
            {{ $this->form }}
        </div>
    </section>
    @if ($filter['search'] && $this->deals->isEmpty())
        <section class="px-[min(6.99vw,50px)] max-w-[1920px] mx-auto py-8 text-center font-light">
            <span class="text-6xl">
                No results for "{{ $filter['search'] }}"<br />
            </span>
        </section>
    @endif
    @if ($this->featuredDeals->isNotEmpty())
        <section class='px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto'>
            <x-hr />
            <h3 class="text-4xl">Featured Deals</h3>
            <div class="h-28"></div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($this->featuredDeals as $deal)
                    <x-deal-card :record="$deal" :record-clicks="true" />
                @endforeach
            </div>
        </section>
    @endif
    @if ($this->deals->isNotEmpty())
        <section class='px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto'>
            <x-hr />
            <div class="flex justify-between items-center">
                <h3 class="text-4xl">Deals</h3>
                <select wire:model.live="sort">
                    <option value="random">Recommended</option>
                    <option value="created_at">What's new</option>
                    <option value="views">Most Popular</option>
                    <option value="percentage">% off</option>
                </select>
            </div>
            <div class="h-28"></div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($this->deals as $deal)
                    <x-deal-card :record="$deal" :record-clicks="true" />
                @endforeach
            </div>
            <div>{{ $this->deals->links() }}</div>
        </section>
    @endif
</div>
