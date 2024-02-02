<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 09:02
 */

declare(strict_types=1);

namespace Sco\Monri\Model\Order;

use Sco\Monri\Model\StringObject;
use Webmozart\Assert\Assert;

final class OrderNumber extends StringObject
{
    public function __construct(string $number)
    {
        Assert::alnum($number, 'Invalid order_number value. Expected alphanumeric. Got: %s');
        Assert::lengthBetween($number, 1, 40, 'Invalid order_number length. Must be between 1-40 characters');
        parent::__construct($number);
    }
}
