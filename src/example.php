<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 18:05
 */

use SasaB\Monri\Client\Request\Authorize;
use SasaB\Monri\Model\Customer;
use SasaB\Monri\Model\Customer\Address;
use SasaB\Monri\Model\Customer\Email;
use SasaB\Monri\Model\Customer\FullName;
use SasaB\Monri\Model\Customer\Phone;
use SasaB\Monri\Model\Order;
use SasaB\Monri\Model\Order\Amount;
use SasaB\Monri\Model\Order\Currency;
use SasaB\Monri\Model\Order\OrderInfo;
use SasaB\Monri\Model\Order\OrderNumber;
use SasaB\Monri\Monri;
use SasaB\Monri\Options;

require_once '../vendor/autoload.php';

// Development
// $monri = Monri::testApi('{authenticity_token}', '{merchant_key}', Options::default());
$monri = Monri::testApi($token = substr(bin2hex(random_bytes(40)), 0, 40), $key = 'xy1234', Options::default());
// Production
// $monri = Monri::api();

$customer = new Customer(
    new FullName('Michael Scott'),
    new Email('michale.scott@gmail.com'),
    new Phone('00387653245'),
    new Address('Dunder Mifflin 1', 'Scranton', '18503', 'USA')
);

bin2hex(random_bytes(40));

$order = new Order(
    new OrderInfo('Paper clips'),
    new OrderNumber('0000001'),
    new Amount(1000),
    new Currency('USD')
);

$request = Authorize::for($customer, $order);
$request->setToken($token);
$request->setKey($key);

echo http_build_query($request->getBody())."\n";

die;

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
// OR
//
$monri->request();
