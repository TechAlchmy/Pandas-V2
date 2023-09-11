<?php

namespace App\Http\Integrations\Cardknox\Requests;

use App\Http\Integrations\Cardknox\CardknoxCustomerConnector;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Request\HasConnector;

class CreatePaymentMethod extends Request implements HasBody
{
    use HasJsonBody;
    use HasConnector;

    protected Method $method = Method::POST;

    protected string $connector = CardknoxCustomerConnector::class;

    public function __construct(
        protected string $customerId,
        protected string $token,
        protected string $tokenType = 'cc',
        protected ?string $exp = null,
        protected ?string $routing = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/CreatePaymentMethod';
    }

    protected function defaultBody(): array
    {
        return [
            'CustomerId' => $this->customerId,
            'Token' => $this->token,
            'TokenType' => $this->tokenType,
            'Exp' => $this->exp,
            'Routing' => $this->routing,
        ];
    }
}
