<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 13:43
 */

namespace SasaB\Monri\Model\Customer;


use SasaB\Monri\Model\StringObject;
use Webmozart\Assert\Assert;

final class Phone extends StringObject
{
    public function __construct(string $phone)
    {
        Assert::alnum($phone, 'Invalid phone value. Expected alphanumeric. Got: %s');
        Assert::lengthBetween($phone, 3, 30, 'Invalid phone length. Must be between 3-30 characters');
        parent::__construct($phone);
    }
}
