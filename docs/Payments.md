## Payments

### Cardknox Payment

We are using the [Transaction endpoint](https://docs.cardknox.com/api/transaction) of Cardknox.

Endpoint: https://x1.cardknox.com/gatewayjson

We are using the [CreditCard/Sale](https://docs.cardknox.com/api/transaction#credit-card) as our payment method/type which requires to pass `xCommand = cc:Sale`
which is currently hardcoded in `App\Services\CardknoxPayment\CardknoxBody` and should changed or dynamically injected to something else when we introduce other method methods/types.

**Useful links:**

-   [Sandbox Account Testing Info and Triggers](https://docs.cardknox.com/#sandbox-account-testing-info-and-triggers)

### Cardknox Payment Usage

`.env`

```env
TRANSACTION_KEY=
```

We have simple service class `App\Services\CardknoxPayment\CardknoxPayment` to charge a User a one time payment via credit card.

```php
/**
 * First prepare a payload or body using `CardknoxBody` DTO, which would be sent to Cardknox transaction endpoint.
 * CardknoxBody DTO is a class with readonly properties and allows to omit optional properties.
 */

$cardknoxPayload = new CardknoxBody([
    'xAmount' => 123.4
    ...
]);

$cardknoxPayment = new CardknoxPayment;
$response = $cardknoxPayment->charge($cardknoxPayload);

if (filled($response->xResult) && $response->xStatus === 'Error') {
    echo 'Error: ' . $response->xError;
} else {
    echo 'Success';
}
```
