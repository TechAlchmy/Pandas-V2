<?php

namespace App\Http\Integrations\Cardknox\Requests;

use App\Http\Integrations\Cardknox\CardknoxConnector;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateCcSale extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    protected string $connector = CardknoxConnector::class;

    public function __construct(
        protected string $email,
        protected string $cardNumber,
        protected string $cardExpired,
        protected string $cvv,
        protected string $amount,

        protected ?string $invoice = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/gatewayjson';
    }

    protected function defaultBody(): array
    {
        return [
            'xCommand' => 'cc:sale',
            'xEmail' => $this->email,
            'xCardNum' => $this->cardNumber,
            'xExp' => $this->cardExpired,
            'xCVV' => $this->cvv,
            'xAmount' => $this->amount,
            'xInvoice' => $this->invoice,
        ];
    }
}
