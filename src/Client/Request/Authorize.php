<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:09
 */

namespace Sco\Monri\Client\Request;

use Sco\Monri\Client\TransactionType;
use Sco\Monri\Model\Customer;
use Sco\Monri\Model\Order;
use Sco\Monri\Options;

final class Authorize extends Form
{
    public function getType(): string
    {
        return TransactionType::AUTHORIZATION;
    }

    public static function for(Customer $customer, Order $order, ?Options $options = null): self
    {
        return new self($customer, $order, $options ?? Options::default());
    }
}
