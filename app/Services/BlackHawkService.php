<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BlackHawkService
{
    protected readonly string $api;
    protected readonly string $clientProgramId;
    protected readonly string $merchantId;
    protected readonly string $cert;
    protected readonly string $certPassword;

    protected static ?self $instance = null;

    public function __construct()
    {
        $this->api = config('services.blackhawk.api');
        $this->clientProgramId = config('services.blackhawk.client_program_id');
        $this->merchantId = config('services.blackhawk.merchant_id');
        $this->cert = config('services.blackhawk.cert');
        $this->certPassword = config('services.blackhawk.cert_password');
    }

    public static function instance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    public static function api()
    {
        $instance = static::instance();

        $headers = [
            'clientProgramId' => $instance->clientProgramId,
            'merchantId' => $instance->merchantId,
            'accept' => 'application/json; charset=utf-8'
        ];

        $response = Http::withHeaders($headers)->withOptions([
                'cert' => [$instance->cert, $instance->certPassword]
            ])
            ->get(
                "{$instance->api}/clientProgram/byKey", 
                ['clientProgramId' => $instance->clientProgramId]);

        return $response;
    }
}
