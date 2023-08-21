<?php

namespace App\Services\CardknoxPayment;

use Illuminate\Support\Arr;

class CardknoxBody
{
    public readonly string $xKey;
    public readonly string $xVersion;
    public readonly string $xSoftwareName;
    public readonly string $xSoftwareVersion;
    public readonly string $xCommand;

    public readonly string $xEmail;
    public readonly ?string $xIP;
    public readonly ?string $xDescription;
    public readonly float $xAmount;
    public readonly ?string $xCurrency;
    public readonly string $xInvoice;

    public readonly string $xCardNum;
    public readonly string $xExp;
    public readonly string $xCVV;

    public readonly ?string $xBillFirstname;
    public readonly ?string $xBillLastname;
    public readonly ?string $xBillCompany;
    public readonly ?string $xBillStreet;
    public readonly ?string $xBillCity;
    public readonly ?string $xBillState;
    public readonly ?string $xBillZip;
    public readonly ?string $xBillCountry;
    public readonly ?string $xBillPhone;

    public readonly ?string $xShipFirstname;
    public readonly ?string $xShipLastname;
    public readonly ?string $xShipCompany;
    public readonly ?string $xShipStreet;
    public readonly ?string $xShipCity;
    public readonly ?string $xShipState;
    public readonly ?string $xShipZip;
    public readonly ?string $xShipCountry;

    public function __construct(array $body)
    {
        $this->xKey = config('services.cardknox.transaction_key');
        $this->xVersion = '5.0.0';
        $this->xSoftwareName = 'Laravel';
        $this->xSoftwareVersion = '10.x';
        $this->xCommand = 'cc:sale'; // $method == 'capture' ? 'cc:sale' : 'cc:authonly';

        collect(get_class_vars(static::class))
            ->except(['xKey', 'xVersion', 'xSoftwareName', 'xSoftwareVersion', 'xCommand'])
            ->each(fn ($value, $property) => $this->{$property} = Arr::get($body, $property));
    }
}
