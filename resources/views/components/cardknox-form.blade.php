<form x-data="@js(['isLoading' => false, 'useNew' => false, 'shouldSavePaymentDetail' => false, 'cardknox_payment_method' => ['cc' => auth()->user()->cardknox_payment_method_cc]])"
    x-on:submit.prevent="
    isLoading = true;
    if (!useNew && cardknox_payment_method.cc != null) {
        $wire.createOrder(
            JSON.parse(
                JSON.stringify(
                    Object.fromEntries(
                        (new FormData($el)).entries()
                    )
                )
            )
        ).then(() => {
            isLoading = false;
        });
        return;
    }
    getTokens(
        () => {
            $wire.createOrder(
                JSON.parse(
                    JSON.stringify(
                        Object.fromEntries(
                            (new FormData($el)).entries()
                        )
                    )
                )
            ).then(() => {
                isLoading = false;
            });
        },
        () => { //onError
            isLoading = false;
        },
        30000, //30 second timeout
    );
    ">
    <x-filament::modal id="cardknox" width="4xl">
        <div class="space-y-6" x-init="setAccount(@js(config('services.cardknox.ifields.key')), @js(config('app.name')), '0.1.1');
        enableAutoFormatting('-');
        let style = {
            display: 'flex',
            border: 'none',
            overflow: 'hidden',
            'font-size': '16px',
            'max-width': '10rem',
        };
        let defaultStyle = style;
        let validStyle = {
            ...style,
            color: 'black',
        };
        let invalidStyle = {
            ...style,
            color: 'red',
        }
        addIfieldKeyPressCallback(function(data) {
            setIfieldStyle('card-number', data.cardNumberFormattedLength <= 0 ? defaultStyle : data.cardNumberIsValid ? validStyle : invalidStyle);
            if (data.cardNumberFormattedLength <= 0) {
                $refs.cardnumber.style.color = 'black';
            } else {
                if (data.cardNumberIsValid) {
                    $refs.cardnumber.style.color = 'black';
                } else {
                    $refs.cardnumber.style.color = 'red';
                    $refs.cardnumber.value = $refs.cardnumber.value.slice(0, -1)
                }
            }
            if (data.lastIfieldChanged === 'cvv'){
                if (data.issuer === 'unknown' || data.cvvLength <= 0) {
                    setIfieldStyle('cvv', defaultStyle);
                    $refs.cvv.style.color = 'red';
                    return;
                }
                setIfieldStyle('cvv', data.cvvIsValid ? validStyle : invalidStyle);
                $refs.cvv.style.color = data.cvvIsValid ? 'black' : 'red';
            } else if (data.lastIfieldChanged === 'card-number') {
                if (data.issuer === 'unknown' || data.cvvLength <= 0) {
                    setIfieldStyle('cvv', defaultStyle);
                    $refs.cvv.style.color = 'black';
                } else if (data.issuer === 'amex'){
                    setIfieldStyle('cvv', data.cvvLength === 4 ? validStyle : invalidStyle);
                    $refs.cvv.style.color = data.cvvLength === 4 ? 'black' : 'red';
                } else {
                    setIfieldStyle('cvv', data.cvvLength === 3 ? validStyle : invalidStyle);
                    $refs.cvv.style.color = data.cvvLength === 3 ? 'black' : 'red';
                }
            } else if (data.lastIfieldChanged === 'ach') {
                setIfieldStyle('ach',  data.achLength === 0 ? defaultStyle : data.achIsValid ? validStyle : invalidStyle);
                $refs.ach.style.color = data.achIsValid === 0 ? 'black' : 'red';
            }
        });
        setIfieldStyle('card-number', style);
        setIfieldStyle('cvv', style);">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium">
                    <div class="flex">
                        <label class="uppercase select-none caret-transparent">
                            Email
                        </label>
                        <span>*</span>
                    </div>
                    <x-input type="text" name="xEmail" value="{{ auth()->user()?->email }}" />
                </div>
                <div x-show="useNew || cardknox_payment_method.cc == null" class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium">
                    <div x-ref="cardnumber" class="flex">
                        <label class="uppercase select-none caret-transparent">
                            Card Number
                        </label>
                        <span>*</span>
                    </div>
                    <iframe class="max-w-[18.2rem] max-h-[1.5rem]" data-ifields-id="card-number" data-ifields-placeholder="Card Number"
                        src="https://cdn.cardknox.com/ifields/{{ config('services.cardknox.ifields.version') }}/ifield.htm"></iframe>
                </div>
                <div x-show="!useNew && cardknox_payment_method.cc != null" class="place-self-center grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h5>Card Number</h5>
                        <span x-text="cardknox_payment_method.cc?.MaskedCardNumber"></span>
                    </div>
                    <div>
                        <h5>Expired</h5>
                        <span x-text="cardknox_payment_method.cc?.Exp.substring(0, 2)"></span>
                        <span>/</span>
                        <span x-text="cardknox_payment_method.cc?.Exp.substring(2)"></span>
                    </div>
                    <input type="hidden" name="xToken" x-bind:value="cardknox_payment_method.cc?.Token" />
                </div>
            </div>
            <div x-show="useNew || cardknox_payment_method.cc == null" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium">
                    <div class="flex">
                        <label class="uppercase select-none caret-transparent">
                            Month
                        </label>
                        <span>*</span>
                    </div>
                    <x-input placeholder="12" name="xExp_month" type="number" minlength="2" maxlength="2" pattern="[0-9]*" autocomplete="cc-exp-month" />
                </div>
                <div class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium">
                    <div class="flex">
                        <label class="uppercase select-none caret-transparent">
                            Year
                        </label>
                        <span>*</span>
                    </div>
                    <x-input placeholder="{{ date('y') }}" name="xExp_year" type="number" minlength="2" maxlength="2" pattern="[0-9]*" autocomplete="cc-exp-year" />
                </div>
                <div class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium">
                    <div x-ref="cvv" class="flex">
                        <label class="uppercase select-none caret-transparent">
                            CVV
                        </label>
                        <span>*</span>
                    </div>
                    <iframe class="max-w-[12rem] max-h-[1.5rem]" data-ifields-id="cvv" data-ifields-placeholder="CVV"
                        src="https://cdn.cardknox.com/ifields/{{ config('services.cardknox.ifields.version') }}/ifield.htm"></iframe>
                </div>
            </div>

            <div>
                <label id="transaction-status"></label>
                <label data-ifields-id="card-data-error" style="color: red;"></label>
            </div>
            <div class="flex flex-col gap-4 lg:flex-row lg:justify-between items-center">
                <div>
                    <x-button class="inline-flex hover:bg-panda-green hover:border-transparent" x-bind:disabled="isLoading" outlined type="submit">
                        <x-filament::loading-indicator x-show="isLoading" class="w-5 h-5" />
                        <span>Submit</span>
                    </x-button>
                    <x-button type="button" x-on:click="$dispatch('close-modal', {id: 'cardknox'})">Cancel</x-button>
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center justify-center gap-4">
                    <div>
                        <x-button type="button" x-show="!useNew && cardknox_payment_method.cc != null" x-on:click="useNew = !useNew">Use Other Card</x-button>
                        <x-button type="button" x-show="useNew" x-on:click="useNew = !useNew">Use Existing Card</x-button>
                        <input name="use_new" type="hidden" x-bind:value="useNew ? 1 : 0" />
                    </div>
                    <div x-show="useNew || cardknox_payment_method.cc == null">
                        <div class="flex gap-2 items-center">
                            <label for="should_save_payment_detail">
                                Save Card Detail?
                            </label>
                            <input class="ring-none" id="should_save_payment_detail" name="should_save_payment_detail" type="checkbox" x-model="shouldSavePaymentDetail" />
                        </div>
                    </div>
                </div>
            </div>
            <input name="xCVV" type="hidden" data-ifields-id="cvv-token" />
            <input name="xCardNum" type="hidden" data-ifields-id="card-number-token" />
            {{-- <input name="xKey" type="hidden" value="{{ config('services.cardknox.ifields.key') }}" /> --}}
        </div>
    </x-filament::modal>
</form>

@pushOnce('scripts')
    <script src='https://cdn.cardknox.com/ifields/{{ config('services.cardknox.ifields.version') }}/ifields.min.js'></script>
@endPushOnce
