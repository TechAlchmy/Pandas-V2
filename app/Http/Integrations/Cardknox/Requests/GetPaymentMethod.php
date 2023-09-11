<?php

namespace App\Http\Integrations\Cardknox\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Request\HasConnector;

class GetPaymentMethod extends Request implements HasBody
{
    use HasJsonBody;
    use HasConnector;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $paymentMethodId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/GetPaymentMethod';
    }

    protected function defaultBody(): array
    {
        return [
            'PaymentMethodId' => $this->paymentMethodId,
        ];
    }
}
