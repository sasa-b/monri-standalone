<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 09:02
 */

namespace SasaB\Monri\Model\Customer;


use SasaB\Monri\Model\StringObject;
use Webmozart\Assert\Assert;

final class FullName extends StringObject
{
    public function __construct(string $fullName)
    {
        Assert::alnum($fullName, 'Invalid full_name value. Expected alphanumeric. Got: %s');
        Assert::lengthBetween($fullName, 3, 30, 'Invalid full_name length. Must be between 3-30 characters');
        parent::__construct($fullName);
    }
}
