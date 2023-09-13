<?php

namespace App\Http\Integrations\Cardknox;

use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Connector;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class CardknoxCustomerConnector extends Connector implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    public function __construct()
    {
        $this->withTokenAuth(config('services.cardknox.transaction_key'), '');
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.cardknox.com/v2';
    }

    protected function defaultHeaders(): array
    {
        return ['X-Recurring-Api-Version' => '2.1'];
    }

    protected function defaultBody(): array
    {
        return [
            'SoftwareName' => config('app.name'),
            'SoftwareVersion' => '0.0.1',
        ];
    }
}
