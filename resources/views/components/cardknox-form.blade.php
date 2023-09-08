<form
    x-on:submit.prevent="
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
            );
        },
        () => { //onError

        },
        30000, //30 second timeout
    );
    ">
    <x-filament::modal id="cardknox" width="4xl">
        <div class="space-y-6" x-init="setAccount(@js(config('services.cardknox.ifields.key')), @js(config('app.name')), '0.1.1');
        let style = {
            display: 'flex',
            border: 'none',
            overflow: 'hidden',
            'font-size': '16px',
            'max-width': '10rem',
        };
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
                <div class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium">
                    <div class="flex">
                        <label class="uppercase select-none caret-transparent">
                            Card Number
                        </label>
                        <span>*</span>
                    </div>
                    <iframe class="max-w-[18rem] max-h-[1.5rem]" data-ifields-id="card-number" data-ifields-placeholder="Card Number"
                        src="https://cdn.cardknox.com/ifields/{{ config('services.cardknox.ifields.version') }}/ifield.htm"></iframe>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                    <div class="flex">
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
            <div>
                <x-button outlined type="submit">Submit</x-button>
            </div>
            <input name="xCVV" type="hidden" data-ifields-id="cvv-token" />
            <input name="xCardNum" type="hidden" data-ifields-id="card-number-token" />
            {{-- <input name="xKey" type="hidden" value="{{ config('services.cardknox.ifields.key') }}" /> --}}
    </x-filament::modal>
</form>

@pushOnce('scripts')
    <script src='https://cdn.cardknox.com/ifields/{{ config('services.cardknox.ifields.version') }}/ifields.min.js'></script>
@endPushOnce