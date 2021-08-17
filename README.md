# monri-standalone

### Usage

```php
require_once '../vendor/autoload.php';

// Development
$monri = Monri::testApi('{authenticity_token}', '{merchant_key}', Options::default());
// Production
$monri = Monri::api('{authenticity_token}', '{merchant_key}', Options::default());

$customer = new Customer(
    new FullName('Michael Scott'),
    new Email('michale.scott@gmail.com'),
    new Phone('00387653245'),
    new Address('Dunder Mifflin 1', 'Scranton', '18503', 'USA')
);

$order = new Order(
    new OrderInfo('Paper clips'),
    new OrderNumber('0000001'),
    new Amount(1000),
    new Currency('USD')
);

//
// API 1
//

// Authorize transaction
$monri->authorize($customer, $order);
// Purchase
$monri->purchase($customer, $order);
// Capture
$monri->capture($order);
// Refund
$monri->refund($order);
// Void
$monri->void($order);

//
// API 2
//

// Authorize transaction
$request = Authorize::for($customer, $order, Options::default());
// Purchase
$request = Purchase::for($customer, $order, Options::default());
// Capture
$request = Capture::for($order);
// Refund
$request = Refund::for($order);
// Void
$request = VoidTransaction::for($order);

$monri->transaction($request);
```

### Environment Variables

If any of the following is set Monri client will read them from the env.

```
MONRI_SUCCESS_URL=
MONRI_CANCEL_URL=
MONRI_CALLBACK_URL=
MONRI_TOKEN=
MONRI_KEY=
MONRI_LANG=
```

### Transaction types

* Authorization
* Purchase
* Capture
* Refund
* Void

Read more at [Monri official documentation](https://ipgtest.monri.com/en/documentation/v2_form)
