<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 09:05
 */

declare(strict_types=1);

namespace Sco\Monri\Model\Order;

use Sco\Monri\Model\IntegerObject;
use Webmozart\Assert\Assert;

final class Amount extends IntegerObject
{
    public function __construct(int $amount)
    {
        Assert::positiveInteger($amount, 'Invalid amount value. Expected positive value. Got: %s');
        Assert::lengthBetween((string) $amount, 3, 11, 'Invalid amount value. Expected number with 3-11 digits. Got: %s');
        parent::__construct($amount);
    }
}
