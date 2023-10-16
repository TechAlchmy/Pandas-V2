@foreach ($orderQueue->gifts as $gift)
@php($discount = App\Models\Discount::firstWhere('code', $gift['contentProviderCode']))

<div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] gap-4">
    <li class="fi-in-repeatable-item block">
        <dl>
            <div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] fi-in-component-ctn gap-6">
                <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
                    <section x-data="{
        isCollapsed: false,
    }" class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" id="apiCalls.0.target-egift-frc" data-has-alpine-state="true">
                        <header class="fi-section-header flex items-center gap-x-3 overflow-hidden px-6 py-4">
                            <img class="fi-section-header-icon self-start fi-color-gray text-gray-400 dark:text-gray-500 h-6 w-6" src="https://panda-static.s3.us-east-2.amazonaws.com/assets/panda_logo.png">
                            <div class="grid flex-1 gap-y-1">
                                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                                    {{$discount?->name}}
                                </h3>
                            </div>
                            <button wire:click="downloadInvoice" class=" text-blue-500 hover:underline">Download</button>
                        </header>
                        <div class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10">
                            <div class="fi-section-content p-6">
                                <dl>
                                    <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-in-component-ctn gap-6">
                                        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                            <div class="fi-in-entry-wrp">
                                                <div class="grid gap-y-2">
                                                    <div class="grid gap-y-2">
                                                        <dd class="">
                                                            <div class="fi-in-image flex items-center gap-x-2.5">
                                                                <div class="flex gap-x-1.5">
                                                                    <img src="{{$discount->media?->first()?->original_url}}" style="height: 200px;" class="max-w-none object-cover object-center ring-white dark:ring-gray-900">
                                                                </div>
                                                            </div>
                                                        </dd>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                            <div>
                                                <dl>
                                                    <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-in-component-ctn gap-6">
                                                        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                                            <div class="fi-in-entry-wrp">
                                                                <div class="grid gap-y-2">
                                                                    <div class="flex items-center justify-between gap-x-3">
                                                                        <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                                                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                                                Amount
                                                                            </span>
                                                                        </dt>
                                                                    </div>
                                                                    <div class="grid gap-y-2">
                                                                        <dd class="">
                                                                            <div class="fi-in-text">
                                                                                <div class="fi-in-affixes flex">
                                                                                    <div class="min-w-0 flex-1">
                                                                                        <div class="">
                                                                                            <div>
                                                                                                <div class="fi-in-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-950 dark:text-white  " style="">
                                                                                                    <div class="">
                                                                                                        $ {{ $gift['amount']}}
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </dd>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                                            <div class="fi-in-entry-wrp">

                                                                <div class="grid gap-y-2">
                                                                    <div class="flex items-center justify-between gap-x-3">
                                                                        <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">


                                                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                                                Access Number
                                                                            </span>

                                                                        </dt>
                                                                    </div>

                                                                    <div class="grid gap-y-2">
                                                                        <dd class="">
                                                                            <div class="fi-in-text">
                                                                                <div class="fi-in-affixes flex">

                                                                                    <div class="min-w-0 flex-1">
                                                                                        <div class="">
                                                                                            <div>
                                                                                                <div class="fi-in-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-950 dark:text-white  " style="">

                                                                                                    <div class="">
                                                                                                        {{ $gift['pin']}}
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </dd>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
                                                            <div class="fi-in-entry-wrp">
                                                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                                    Card Number
                                                                </span>

                                                                <div class="grid gap-y-2">

                                                                    <div class="grid gap-y-2">
                                                                        <dd class="">
                                                                            <div class="fi-in-text">
                                                                                <div class="fi-in-affixes flex">

                                                                                    <div class="min-w-0 flex-1">
                                                                                        <div class="">
                                                                                            <div>
                                                                                                <div class="fi-in-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-950 dark:text-white  " style="">

                                                                                                    <div class="prose max-w-none dark:prose-invert [&amp;>*:first-child]:mt-0 [&amp;>*:last-child]:mb-0 prose-sm">
                                                                                                        # {{ substr($gift['cardNumber'], 0, -1 * strlen($gift['pin']))}}
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </dd>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                                            <div class="fi-in-entry-wrp">

                                                                <div class="grid gap-y-2">
                                                                    <div class="flex items-center justify-between gap-x-3">
                                                                        <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">

                                                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                                                Scan code
                                                                            </span>

                                                                        </dt>
                                                                        <!--[if ENDBLOCK]><![endif]-->

                                                                        <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                                                    </div>
                                                                    <!--[if ENDBLOCK]><![endif]-->

                                                                    <div class="grid gap-y-2">
                                                                        <dd class="">
                                                                            <!--[if BLOCK]><![endif]-->
                                                                            <div class="fi-in-image flex items-center gap-x-2.5">
                                                                                <!--[if BLOCK]><![endif]-->
                                                                                <div class="flex gap-x-1.5">
                                                                                    <!--[if BLOCK]><![endif]--> <img src="{{ barCodeGenerator($gift['cardNumber'])}}" style="height: 25px;" class="max-w-none object-cover object-center ring-white dark:ring-gray-900">
                                                                                    <!--[if ENDBLOCK]><![endif]-->

                                                                                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                                                                </div>

                                                                                <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                                                                <!--[if ENDBLOCK]><![endif]-->
                                                                            </div>
                                                                            <!--[if ENDBLOCK]><![endif]-->
                                                                        </dd>

                                                                        <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                </dl>

                                            </div>
                                        </div>
                                        <!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </section>
                </div>
                <!--[if ENDBLOCK]><![endif]-->
            </div>
        </dl>

    </li>
    <!--[if ENDBLOCK]><![endif]-->
</div>
@endforeach