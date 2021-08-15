<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:09
 */

namespace SasaB\Monri\Client\Request;

use SasaB\Monri\Client\TransactionType;
use SasaB\Monri\Model\Customer;
use SasaB\Monri\Model\Order;
use SasaB\Monri\Options;

final class Purchase extends Form
{
    public function getType(): string
    {
        return TransactionType::PURCHASE;
    }

    public static function for(Customer $customer, Order $order, Options $options = null): Purchase
    {
        return new self($customer, $order, $options ?? Options::default());
    }
}
