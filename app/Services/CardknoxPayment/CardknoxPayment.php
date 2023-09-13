<?php

namespace App\Services\CardknoxPayment;

use Illuminate\Support\Facades\Http;

class CardknoxPayment
{
    public function charge(CardknoxBody $cardknoxBody)
    {
        $url = 'https://x1.cardknox.com/gatewayjson';

        return Http::post($url, $cardknoxBody);
    }
}
