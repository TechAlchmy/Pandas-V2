<?php

namespace App\Http\Integrations\Cardknox\Requests;

use App\Http\Integrations\Cardknox\CardknoxConnector;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Request\HasConnector;

class UpdateCustomer extends Request implements HasBody
{
    use HasJsonBody;
    use HasConnector;

    protected Method $method = Method::POST;

    protected string $connector = CardknoxConnector::class;

    public function resolveEndpoint(): string
    {
        return '/UpdateCustomer';
    }
}
