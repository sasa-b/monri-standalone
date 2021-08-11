<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 18:05
 */

use SasaB\Monri\Model\Customer\Address;
use SasaB\Monri\Model\Customer\Email;
use SasaB\Monri\Model\Customer\FullName;
use SasaB\Monri\Model\Customer\Phone;
use SasaB\Monri\Model\Order;
use SasaB\Monri\Model\Order\Amount;
use SasaB\Monri\Model\Order\Currency;
use SasaB\Monri\Model\Order\OrderInfo;
use SasaB\Monri\Model\Order\OrderNumber;
use SasaB\Monri\Options;

require_once 'vendor/autoload.php';

$monri = \SasaB\Monri\Monri::api();

$customer = new \SasaB\Monri\Model\Customer(
    new FullName(),
    new Email(),
    new Phone(),
    new Address()
);

$order = new Order(
    new OrderInfo(),
    new OrderNumber(),
    new Amount(),
    new Currency()
);

$monri->authorize($customer, $order, Options::default());
