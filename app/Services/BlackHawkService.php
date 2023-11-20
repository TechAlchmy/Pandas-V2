<?php

namespace App\Services;

use App\Enums\BlackHawkApiType;
use App\Enums\BlackHawkOrderStatus;
use App\Enums\OrderStatus;
use App\Models\ApiCall;
use App\Models\OrderQueue;
use Illuminate\Support\Facades\Http;

class BlackHawkService
{
    protected readonly string $catalogApi;
    protected readonly string $realtimeOrderApi;
    protected readonly string $bulkOrkderApi;
    protected readonly string $retreiveCardApi;
    protected readonly string $clientProgramId;
    protected readonly string $merchantId;
    protected readonly string $cert;
    protected readonly string $certPassword;

    protected static ?self $instance = null;

    const DUMMY_URL_PREFIX = 'Please_Replace_This_';

    public function __construct()
    {
        $this->catalogApi = config('services.blackhawk.catalog_api');
        $this->realtimeOrderApi = config('services.blackhawk.realtime_order_api');
        $this->bulkOrkderApi = config('services.blackhawk.bulk_order_api');
        $this->retreiveCardApi = config('services.blackhawk.retreive_card_api');
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

    // This is the catalog endpoint for egift cards
    public static function catalog()
    {
        $instance = static::instance();

        $result = [];

        $requestId = uniqid();
        $headers = [
            'requestId' => $requestId, // This should be a unique id from our api call log
            'merchantId' => $instance->merchantId,
            'accept' => 'application/json; charset=utf-8'
        ];

        ApiCall::create([
            'api' => BlackHawkApiType::Catalog,
            'request_id' => $requestId,
            'response' => null,
            'success' => null,
            'created_at' => now()
        ]);

        $promise = Http::async()->withHeaders($headers)->withOptions([
            'cert' => [$instance->cert, $instance->certPassword]
        ])
            ->get(
                "$instance->catalogApi",
                ['clientProgramId' => $instance->clientProgramId]
            )->then(
                function ($response) use (&$result) {
                    $result = [
                        'response' => $response->json(),
                        'success' => $response->ok(),
                        'status_code' => $response->status()
                    ];
                    ApiCall::where('api', 'catalog')->orderBy('id', 'desc')->first()->update($result);
                }
            );

        $promise->wait();
        return $result;
    }

    // This is the place order endpoint for egift cards in realtime where we used to wait before recieving response
    // public static function order(Order $order, ?string $previousReq = null)
    // {
    //     // There should be a waiting period such that the last request has a response received (not null) before retyring the api call again
    //     $instance = static::instance();

    //     $result = [];

    //     $requestId = uniqid();
    //     $headers = [
    //         'requestId' => $requestId, // This should be a unique id from our api call log
    //         'clientProgramNumber' => $instance->clientProgramId,
    //         'millisecondsToWait' => 15000,
    //         'merchantId' => $instance->merchantId,
    //         'SYNCHRONOUS_ONLY' => 'true',
    //         'Content-Type' => 'application/json'
    //     ];

    //     $refId = uniqid();
    //     $order->loadMissing('orderDetails.discount');
    //     $orderDetails = $order->orderDetails->map(function ($orderDetail) use ($refId) {
    //         return [
    //             'clientRefId' => (string) $refId,
    //             'quantity' => (string) $orderDetail->quantity,
    //             'amount' => (string) ($orderDetail->amount / 100),
    //             'contentProvider' => (string) $orderDetail->discount->code
    //         ];
    //     });

    //     $reqData = [
    //         'clientProgramNumber' => $instance->clientProgramId,
    //         'paymentType' => 'ACH_DEBIT',
    //         'returnCardNumberAndPIN' => 'true',
    //         'orderDetails' => $orderDetails,
    //     ];

    //     ApiCall::create([
    //         'api' => BlackHawkApiType::RealtimeOrder,
    //         'request_id' => $requestId,
    //         'order_id' => $order->id,
    //         'response' => null,
    //         'success' => null,
    //         'created_at' => now(),
    //         'request' => $reqData
    //     ]);

    //     $promise = Http::async()->withHeaders($headers)->withOptions([
    //         'cert' => [$instance->cert, $instance->certPassword]
    //     ])
    //         ->post(
    //             $instance->realtimeOrderApi,
    //             $reqData
    //         )->then(
    //             function ($response) use (&$result) {
    //                 $result = [
    //                     'response' => $response->json(),
    //                     'success' => $response->created(), // $resposne->accepeted() or 202 we treat as failure
    //                 ];
    //                 ApiCall::where('api', 'order')->orderBy('id', 'desc')->first()->update($result);
    //             }
    //         );

    //     $promise->wait();
    //     return $result;
    // }

    // This is the place order endpoint for egift cards in realtime after this was added to queue
    public static function realtimeOrder(OrderQueue $orderQueue): void
    {
        $instance = static::instance();

        $requestId = uniqid();

        $orderQueue->start($requestId);

        $headers = [
            'requestId' => $requestId, // This should be a unique id from our api call log
            'clientProgramNumber' => $instance->clientProgramId,
            'millisecondsToWait' => '15000',
            'merchantId' => $instance->merchantId,
            // 'SYNCHRONOUS_ONLY' => 'false', 
            'Content-Type' => 'application/json'
        ];

        $orderQueue->loadMissing('order.orderDetails.discount');

        $orderDetails = $orderQueue->order->orderDetails->where('discount.is_bhn', true)
            ->map(function ($orderDetail) {
                return [
                    'quantity' => (string) $orderDetail->quantity,
                    'amount' => (string) ($orderDetail->amount / 100),
                    'contentProvider' => (string) $orderDetail->discount->code
                ];
            })->values();

        $reqData = [
            'clientProgramNumber' => $instance->clientProgramId,
            'paymentType' => 'ACH_DEBIT',
            'returnCardNumberAndPIN' => 'true',
            'orderDetails' => $orderDetails,
        ];

        $apiCall = ApiCall::create([
            'api' => BlackHawkApiType::RealtimeOrder,
            'request_id' => $requestId,
            'request' => $reqData,
            'response' => null,
            'success' => null,
            'created_at' => now(),
            'order_id' => $orderQueue->order_id,
            'order_queue_id' => $orderQueue->id,
        ]);

        $promise = Http::withHeaders($headers)->withOptions([
            'cert' => [$instance->cert, $instance->certPassword]
        ])
            ->post(
                $instance->realtimeOrderApi,
                $reqData
            );

        $success = $promise->created() || $promise->accepted();
        $apiCall->update([
            'response' => $promise->json(),
            'success' => $success,
            'status_code' => $promise->status()
        ]);

        $orderQueue->stop($success);
    }

    // TODO: Refactor duplicate code of realtime and bulk order APIs

    public static function bulkOrder(OrderQueue $orderQueue): void
    {
        $instance = static::instance();

        $requestId = uniqid();

        $orderQueue->start($requestId);

        $headers = [
            'requestId' => $requestId, // This should be a unique id from our api call log
            'merchantId' => $instance->merchantId,
            'Content-Type' => 'application/json',
            'millisecondsToWait' => '15000'
        ];

        $orderQueue->loadMissing('order.orderDetails.discount');

        $orderDetails = $orderQueue->order->orderDetails->where('discount.is_bhn', true)
            ->map(function ($orderDetail) {
                return [
                    'quantity' => (string) $orderDetail->quantity,
                    'amount' => (string) ($orderDetail->amount / 100),
                    'contentProvider' => (string) $orderDetail->discount->code
                ];
            })->values();

        $reqData = [
            'clientProgramNumber' => $instance->clientProgramId,
            'paymentType' => 'ACH_DEBIT',
            'returnCardNumberAndPIN' => 'true',
            'orderDetails' => $orderDetails,
        ];

        $apiCall = ApiCall::create([
            'api' => BlackHawkApiType::BulkOrder,
            'request_id' => $requestId,
            'request' => $reqData,
            'response' => null,
            'success' => null,
            'created_at' => now(),
            'order_id' => $orderQueue->order_id,
            'order_queue_id' => $orderQueue->id,
        ]);

        $promise = Http::withHeaders($headers)->withOptions([
            'cert' => [$instance->cert, $instance->certPassword]
        ])
            ->post(
                $instance->bulkOrkderApi,
                $reqData
            );

        $success = $promise->created() || $promise->accepted();
        $apiCall->update([
            'response' => $promise->json(),
            'status_code' => $promise->status(),
            'success' => $success
        ]);

        $orderQueue->stop($success);
    }

    public static function cardInfo(OrderQueue $orderQueue): void
    {
        $instance = static::instance();

        $requestId = $orderQueue->request_id;

        $headers = [
            'requestId' => $requestId, // This should be a unique id from our api call log
            'merchantId' => $instance->merchantId,
            'Content-Type' => 'application/json'
        ];

        $reqData = [
            'clientProgramNumber' => $instance->clientProgramId,
            'requestId' => $requestId
        ];

        $promise = Http::withHeaders($headers)->withOptions([
            'cert' => [$instance->cert, $instance->certPassword]
        ])
            ->get(
                $instance->retreiveCardApi,
                $reqData
            );

        if ($promise->ok()) {
            $response = $promise->json();

            if (!empty($response['orderStatus'])) {
                $orderStatus = $response['orderStatus'];
            } else {
                $orderStatus = !empty($response['eGifts'])
                    ? BlackHawkOrderStatus::Complete->value
                    : BlackHawkOrderStatus::Default->value;
            }

            if ($orderStatus === BlackHawkOrderStatus::Failure->value) {
                $order = $orderQueue->load('order.orderDetails.orderDetailRefund.discount.brand')->order;
                $order->createBhRefundRequest();
            }
            $orderQueue->update([
                'order_status' => $orderStatus,
                'fetched_at' => now(),
                'gifts' => $response['eGifts'] ?? $orderQueue->gifts,
                'last_info' => $response
            ]);

            if ($orderStatus === BlackHawkOrderStatus::Complete->value) {
                $orderQueue->order->update([
                    'order_status' => OrderStatus::Completed->value
                ]);
            }
        }
    }
}
