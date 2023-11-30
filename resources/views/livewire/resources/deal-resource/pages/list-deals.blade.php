<div>
    <section class="px-[min(6.99vw,50px)] py-8 bg-neutral-200 lg:sticky lg:top-28 lg:z-10">
        <div class="max-w-[1920px] mx-auto lg:flex lg:justify-between lg:gap-6">
            <h1 class="mb-8 text-2xl text-center lg:text-4xl lg:text-start lg:mb-0">
                Live More with Panda-Powered Deals + Discounts
            </h1>
            <div>
                {{ $this->form }}
            </div>
        </div>
    </section>
    @if ($this->deals->isEmpty())
    <section x-data="{ shown: false }" x-intersect.once="shown = true" class="px-[min(6.99vw,50px)] max-w-[1920px] mx-auto py-8 text-center font-light">
        <span x-show="shown" x-transition.opacity.duration.1000ms class="text-6xl break-words">
            @if ($filter['search'])
            No results for "{{ $filter['search'] }}"<br />
            @else
            No Deals
            @endif
        </span>
    </section>
    @endif
    @if (!$this->hasActiveFilter)
    <x-deals-section title="Featured Deals" class="bg-white" :records="$this->featuredDeals" />
    @endif
    @if ($this->deals->isNotEmpty())
    <section class='px-[min(6.99vw,50px)] py-8'>
        <div class="max-w-[1920px] mx-auto">
            <x-hr />
            <div class="flex items-center justify-between">
                <h3 class="text-6xl font-editorial">
                    @if (empty($this->filter['category_id']))
                    Deals
                    @else
                    {{ \App\Models\Category::find($this->filter['category_id'])->name }}
                    @endif
                </h3>
                <select wire:model.live="sort">
                    <option value="created_at">What's new</option>
                    <option value="random">Recommended</option>
                    <option value="views">Most Popular</option>
                    <option value="percentage">% off</option>
                </select>
            </div>
            <div class="h-10"></div>
            <div class="grid grid-cols-2 gap-6 lg:grid-cols-4">
                @foreach ($this->deals as $deal)
                <x-deal-card :record="$deal" />
                @endforeach
            </div>
            <div class="h-16"></div>
            <div>{{ $this->deals->links() }}</div>
        </div>
    </section>
    @endif
    <x-deals-section title="Recently Viewed" :records="$this->recentlyViewed" />

    <section class="px-[min(6.99vw,50px)] py-8">
        <div class="max-w-[1920px] mx-auto">
            <div class="lg:flex lg:justify-between lg:gap-6">
                <h1 class="mb-8 text-3xl text-center lg:text-6xl font-editorial lg:text-start lg:mb-0">
                    Real people<br />enjoying real perks
                </h1>
                <div class="lg:w-1/4">
                    <div>Show us how Panda helps you live more life on your terms! Tag us @PandaPeople on Instagram and TikTok.</div>
                </div>
            </div>
            <div class="py-2 mx-auto lg:pt-8">
                <div class="flex flex-wrap -m-1 md:-m-2">
                    <div class="flex flex-wrap lg:w-1/2">
                        <div class="w-1/2 p-1 md:p-2">
                            <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg" src="https://tecdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(70).webp" />
                        </div>
                        <div class="w-1/2 p-1 md:p-2">
                            <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg" src="https://tecdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(72).webp" />
                        </div>
                        <div class="w-full p-1 md:p-2">
                            <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg" src="https://tecdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(73).webp" />
                        </div>
                    </div>
                    <div class="flex flex-wrap lg:w-1/2">
                        <div class="w-full p-1 md:p-2">
                            <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg" src="https://tecdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(74).webp" />
                        </div>
                        <div class="w-1/2 p-1 md:p-2">
                            <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg" src="https://tecdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(75).webp" />
                        </div>
                        <div class="w-1/2 p-1 md:p-2">
                            <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg" src="https://tecdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(77).webp" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-28"></div>
        </div>
    </section>
</div>