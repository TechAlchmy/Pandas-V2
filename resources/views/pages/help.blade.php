<?php
use function Laravel\Folio\{name};

name('help');
?>
<x-layouts.app>
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div>
            <div class="lg:min-h-screen bg-cover" style="background-image: url({{ getMediaPath('assets/help-page-banner.png') }})">
                <div class="p-8 flex flex-col justify-between gap-16">
                    <h1 class="text-6xl font-editorial">Help</h1>
                    <p>
                        Panda People supplies benefits that are offered through your employer. If you’re not finding what you’re looking for on your My Benefits page, please reach out to your employer.

                        For anything else you need help with on your Panda Portal, such as help with checking out or finding deals, search our FAQ or get in touch with us.
                    </p>
                </div>
            </div>
        </div>
        <div class="p-8 space-y-4 max-h-[100vh] overflow-y-scroll">
            <h2 class="text-6xl font-light font-editorial">Frequently Asked Questions</h2>
            <ul class="divide-y" x-data="{ selected: null }">
                @foreach ([
                    'Discounts and Codes' => [
                        'What are Panda Discounts?' => "Panda People provides instant discounts for one-time purchases at select merchants. These discounts are available as codes to enter at checkout, or links that redirect you to the merchant site with discounts already applied. On some merchant sites, you may see a pop-up or banner showing your discount has been applied. 

                        All available Panda Discounts can be found on the Deals page. 
                        ",
                        'How to redeem a discount?' => 'First, click on “See Deal” on the discount you want to redeem. 

                        To redeem your Panda discount, click “Redeem Now” on the deal you want to use.

                        You will either be redirected to the merchant store or see a code to use when checking out.

                        When you are redirected to a merchant store from a deal, your discount code is automatically applied to your order. 

                        When provided with a discount code, copy and paste the code at checkout on the merchant’s online store. Your discount will then be applied.
                        ',
                        'My discount code isn’t working' => '
                        Discount codes are often case sensitive, so make sure you’re entering them exactly as they appear on the Panda site. If you’re copying and pasting the code and it isn’t working, try entering it in manually. 

                        There may be restrictions for certain discount codes, so it’s important to read the restriction to make sure your purchase qualifies. 

                        If you’ve checked everything and your code still isn’t working, contact us to resolve the issue before making a purchase. We cannot apply discounts after a purchase has been made.
                        ',
                    ],
                    'E-Vouchers' => [
                        'What are e-vouchers?' => "An e-voucher is an electronic gift card you can use to pay for items with select merchants. 

                        With Panda People, your employer contributes a portion to your e-voucher, saving you on the total amount you pay to the merchant. For example, if you select a $50 e-voucher and there are 10% savings, you only pay $45 for a $50 e-voucher. 

                        E-vouchers are delivered instantly to your inbox and also appear on your “My Panda” page under the “My Deals” section.

                        E-vouchers are treated as cash, so once your order has been processed with the merchant, Panda People cannot accept the voucher for security purposes. 

                        E-vouchers can only be applied on the merchant’s online store or in person (where available). 

                        Once e-vouchers have been emailed to you, we cannot retrieve lost e-vouchers or assign them to anyone else. 
                        ",
                        'How to buy an e-voucher?' => 'E-vouchers can be purchased on the Panda website for use with select merchants.                

                        1. Begin by selecting the e-voucher you want.
                        <br/>
                        2. You will be taken to the voucher’s product page.
                        <br/>
                        3. Enter the amount of credit you would like with the merchant store or select the value from the dropdown menu. You will see the amount of savings available on the total amount. For example, if you select $50 and there are 10% savings, you only pay $45 for a $50 e-voucher. 
                        <br/>
                        4. Click on “Redeem” to add your e-voucher to your cart.
                        <br/>
                        5. Checking out on Panda People works like every other online store. Once your purchase has been completed, you will receive an email confirmation.
                        <br/>
                        6. You will be asked which email address to send the e-voucher to. This can be used if you would like to gift someone an e-voucher or send it to another email address. 
                        <br/>
                        7. Please confirm you are sending the e-voucher to the correct email address as this cannot be reversed after purchase. 
                        <br/>
                        8. After purchasing your e-voucher, it will be immediately emailed to you at the email address you provided at checkout. 
                        <br/>
                        9. Your purchased e-vouchers will also appear on your “My Panda” page under “My Deals”.
                        <br/>
                        10. You may receive additional instructions or details on how to use your e-voucher in the email containing your e-voucher.

                        Note: Some restrictions may apply to the purchase of some e-vouchers. Please see the e-voucher’s product page for more details. 
                        ',
                        'I didn’t receive my e-voucher' => 'In the event your e-voucher didn’t arrive in your email inbox, please confirm the email address used in your order. You can check this in your order confirmation or under the “My Deals” section in your Panda Portal. 

                        If the email you entered was incorrect, please contact us at __________.
                        ',
                        'How to use your e-voucher' => 'For onlline, Your e-voucher will be emailed to you and include a code for online use. 

                        Using your e-voucher is just like any other gift card.

                        When purchasing something on the merchant’s online store, enter the e-voucher code included in your email in the gift card section at checkout.

                        You may be asked how much of the e-voucher you wish to use toward your total amount. Enter this amount before checking out. If you don’t enter a specific amount or this option isn’t available, the total credit on your e-voucher will be applied to your total. 

                        If you have a remaining balance on your e-voucher, this may appear on your purchase receipt. 

                        In-Store
                        Some e-vouchers will also include a barcode or QR code for use in a merchant’s physical store.

                        When checking out, present the barcode or QR code in your email for the cashier to scan. 

                        You may be asked how much of your e-voucher’s credit you want to use toward your purchase. 

                        If you have a remaining balance after checking out, this may appear on your purchase receipt.

                        Note: Use of e-vouchers in merchant stores may differ. Please see the email containing your e-voucher or the merchant\'s website for further details on using your e-voucher in store. 
                        ',
                    ],
                    'Account and Login' => [
                        'I can’t find my benefits ' => 'Panda provides employer-sponsored benefits and retail discounts and vouchers that you can find on this site or on your “My Panda” page under the “My Benefits” tab. If you are having difficulty finding a benefit, contact your employer to make sure it is being offered through Panda People. 

                        Not all discounts, vouchers, and benefits are available to all Panda members.',
                        'How to update your personal information and password' => 'Navigate to the “My Panda” page to access your personal details and security settings. You can change your contact information, login details, and security settings here.',
                    ],
                    'Other' => [
                        'Disclaimer' => 'Discounted rates for e-vouchers are for purchase directly from Panda People only. Some merchants sell e-vouchers, gift cards, and other vouchers directly on their website, but these are not available at the same discounted rates that are offered with Panda People. 

                        Panda People does not offer any warranties on products or services purchased through the Panda People website or merchant websites. We may or may not promote certain deals, discounts, or products but Panda People is not affiliated with any particular merchant. 

                        Panda People strives to make things as clear as possible, but we have no control over information provided by merchants in regards to e-vouchers and their terms of use. 

                        Please check all terms and conditions that apply to any particular e-voucher you are considering purchasing through Panda People. These can be checked on the e-voucher product page or on the merchant’s website. 

                        All discounts and e-vouchers are exclusively offered through Panda People for registered members only.',
                    ],
                ] as $title => $questions)
                    <li class="py-6 space-y-4">
                        <div>
                            <button class="text-xl" x-on:click="selected = (selected == {{ $loop->index }}) ? null : {{ $loop->index }}">{{ $title }}</button>
                        </div>
                        <div class="space-y-3" x-show="selected == {{ $loop->index }}" x-cloak x-transition>
                            @foreach ($questions as $questionTitle => $answer)
                                <h4 class="font-bold">{{ $questionTitle }}</h4>
                                <p>{!! $answer !!}</p>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="flex items-center justify-between">
                <p>Not finding what you're looking for?</p>
                <x-link href="/contact-us" outlined class="hover:bg-panda-green hover:border-transparent">Contact Us!</x-link>
            </div>
        </div>
    </div>
</x-layouts.app>
