<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 09:02
 */

declare(strict_types=1);

namespace SasaB\Monri\Model\Order;

use SasaB\Monri\Model\StringObject;
use Webmozart\Assert\Assert;

final class OrderInfo extends StringObject
{
    public function __construct(string $info)
    {
        Assert::lengthBetween($info, 3, 100, 'Invalid order_info length. Must be between 3-100 characters');
        parent::__construct($info);
    }
}
