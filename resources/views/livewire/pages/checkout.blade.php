<div class="max-w-[1920px] mx-auto px-[min(6.99vw,50px)] py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="col-span-2">
            <h2 class="font-light text-3xl">Shopping bags</h2>
            <x-hr />
            @if (cart()->items()->isNotEmpty())
                <div class="divide-y">
                    <div class="p-4 hidden lg:block">
                        <div class="flex gap-6">
                            <div class="w-20"></div>
                            <div class="w-full grid grid-cols-1 lg:grid-cols-4 gap-6">
                                <div>Item</div>
                                <div>Item Price</div>
                                <div>Qty</div>
                                <div>Total Price</div>
                            </div>
                        </div>
                    </div>
                    @foreach (cart()->items() as $item)
                        <div class="p-4">
                            <div class="flex gap-6">
                                <img src="https://tailwindui.com/img/ecommerce-images/checkout-page-02-product-01.jpg" alt="Front of men&#039;s Basic Tee in black." class="w-20 rounded-md">
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                    <div>
                                        <h5>Item {{ $loop->index }}</h5>
                                    </div>
                                    <div>
                                        {{ Filament\Support\format_money(60, 'USD') }}
                                    </div>
                                    <div>
                                        <x-input type="number" class="px-2 max-w-full border !border-solid border-black" min="1" />
                                    </div>
                                    <div>
                                        {{ Filament\Support\format_money(60, 'USD') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-6">
                                <button>Remove</button>
                                <button>Save for later</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div>No Item</div>
            @endif
        </div>
        <div>
            <h2 class="font-light text-3xl">Order Summary</h2>
            <x-hr />
            <table class="table w-full">
                <tbody>
                    <tr>
                        <td>Subtotal</td>
                        <td align="right">{{ Filament\Support\format_money(60, 'USD') }}</td>
                    </tr>
                    <tr>
                        <td>Shipping</td>
                        <td align="right">{{ Filament\Support\format_money(60, 'USD') }}</td>
                    </tr>
                    <tr>
                        <td>Est. Tax</td>
                        <td align="right">{{ Filament\Support\format_money(60, 'USD') }}</td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td align="right">{{ Filament\Support\format_money(60, 'USD') }}</td>
                    </tr>
                </tbody>
            </table>
            <x-hr />
            <x-button outlined>
                Proceed to checkout
            </x-button>
        </div>
    </div>
</div>
{{-- <div class="bg-gray-50">
    <div class="container mx-auto px-4 pb-24 pt-16 sm:px-6 lg:px-8">
        <h2 class="sr-only">Checkout</h2>

        <form wire:submit="confirmOrder" class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
            <div>
                {{ $this->form }}
            </div>

            <!-- Order summary -->
            <div class="mt-10 lg:mt-0">
                <h2 class="text-lg font-medium text-gray-900">Order summary</h2>

                <div class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm">
                    <h3 class="sr-only">Items in your cart</h3>
                    <ul role="list" class="divide-y divide-gray-200">
                        <li class="flex px-4 py-6 sm:px-6">
                            <div class="flex-shrink-0">
                                <img src="https://tailwindui.com/img/ecommerce-images/checkout-page-02-product-01.jpg" alt="Front of men&#039;s Basic Tee in black." class="w-20 rounded-md">
                            </div>

                            <div class="ml-6 flex flex-1 flex-col">
                                <div class="flex">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm">
                                            <a href="#" class="font-medium text-gray-700 hover:text-gray-800">Basic Tee</a>
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-500">Black</p>
                                        <p class="mt-1 text-sm text-gray-500">Large</p>
                                    </div>

                                    <div class="ml-4 flow-root flex-shrink-0">
                                        <button type="button" class="-m-2.5 flex items-center justify-center bg-white p-2.5 text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Remove</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-1 items-end justify-between pt-2">
                                    <p class="mt-1 text-sm font-medium text-gray-900">$32.00</p>

                                    <div class="ml-4">
                                        <label for="quantity" class="sr-only">Quantity</label>
                                        <select id="quantity" name="quantity"
                                            class="rounded-md border border-gray-300 text-left text-base font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- More products... -->
                    </ul>
                    <dl class="space-y-6 border-t border-gray-200 px-4 py-6 sm:px-6">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">$64.00</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm">Shipping</dt>
                            <dd class="text-sm font-medium text-gray-900">$5.00</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm">Taxes</dt>
                            <dd class="text-sm font-medium text-gray-900">$5.52</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                            <dt class="text-base font-medium">Total</dt>
                            <dd class="text-base font-medium text-gray-900">$75.52</dd>
                        </div>
                    </dl>

                    <div class="flex justify-center border-t border-gray-200 px-4 py-6 sm:px-6">
                        <x-button type="submit" size="lg" outlined class="mt-4 inline-block">
                            Confirm order
                        </x-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div> --}}
