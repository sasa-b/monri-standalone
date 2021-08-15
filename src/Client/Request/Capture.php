<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:13
 */

namespace SasaB\Monri\Client\Request;

use SasaB\Monri\Client\TransactionType;
use SasaB\Monri\Model\Order;

final class Capture extends Xml
{
    public function getType(): string
    {
        return TransactionType::CAPTURE;
    }

    public static function for(Order $order): Capture
    {
        return new self($order);
    }
}
