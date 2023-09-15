<?php

namespace App\Http\Integrations\Cardknox\Requests;

use App\Http\Integrations\Cardknox\CardknoxConnector;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Request\HasConnector;

class CreateCcRefund extends Request implements HasBody
{
    use HasConnector;
    use HasJsonBody;

    protected Method $method = Method::POST;

    protected string $connector = CardknoxConnector::class;

    public function __construct(
        protected string $ref,
        protected string $amount,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/gatewayjson';
    }

    protected function defaultBody(): array
    {
        return [
            'xCommand' => 'cc:Refund',
            'xAmount' => $this->amount,
            'xRefNum' => $this->ref,
        ];
    }
}
