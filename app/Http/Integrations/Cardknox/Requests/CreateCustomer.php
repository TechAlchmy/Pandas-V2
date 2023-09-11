<?php

namespace App\Http\Integrations\Cardknox\Requests;

use App\Http\Integrations\Cardknox\CardknoxCustomerConnector;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Request\HasConnector;

class CreateCustomer extends Request implements HasBody
{
    use HasJsonBody;
    use HasConnector;

    protected Method $method = Method::POST;

    protected string $connector = CardknoxCustomerConnector::class;

    public function __construct(
        protected string $firstName,
        protected string $lastName,
        protected string $companyName,
        protected ?string $customerNumber = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/CreatePaymentMethod';
    }

    protected function defaultBody(): array
    {
        return [
            'BillFirstName' => $this->firstName,
            'BillLastName' => $this->lastName,
            'BillCompany' => $this->companyName,
            'CustomerNumber' => $this->customerNumber,
        ];
    }
}
