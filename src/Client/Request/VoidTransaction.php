<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:13
 */

namespace Sco\Monri\Client\Request;

use Sco\Monri\Client\TransactionType;
use Sco\Monri\Model\Order;

final class VoidTransaction extends Xml
{
    public function getType(): string
    {
        return TransactionType::VOID;
    }

    public static function for(Order $order): VoidTransaction
    {
        return new self($order);
    }
}
