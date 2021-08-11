<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 09:05
 */

declare(strict_types=1);

namespace SasaB\Monri\Model\Order;

use SasaB\Monri\Model\StringObject;
use Webmozart\Assert\Assert;

final class Currency extends StringObject
{
    public const USD = 'USD';
    public const BAM = 'BAM';
    public const HRK = 'HRK';
    public const EUR = 'EUR';

    public function __construct(string $currency)
    {
        Assert::inArray($currency, [
            'USD', 'EUR', 'BAM', 'HRK'
        ], 'Invalid currency value. Expected USD, EUR, BAM or HRK. Got: %s');
        parent::__construct($currency);
    }
}
