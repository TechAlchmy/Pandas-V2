<?php

namespace App\Http\Integrations\Cardknox;

use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Connector;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class CardknoxConnector extends Connector implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    public function resolveBaseUrl(): string
    {
        return 'https://x1.cardknox.com';
    }

    protected function defaultBody(): array
    {
        return [
            'xKey' => config('services.cardknox.transaction_key'),
            'xVersion' => '5.0.0',
            'xSoftwareName' => config('app.name'),
            'xSoftwareVersion' => '0.0.1',
        ];
    }
}
