<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 15. 8. 2021.
 * Time: 09:59
 */

namespace Sco\Monri\Client;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

final class KebabCaseToCamelCaseConverter implements NameConverterInterface
{
    public function normalize(string $propertyName): string
    {
        return strtolower((string) preg_replace('/([A-Z])/', '-$1', $propertyName));
    }

    public function denormalize(string $propertyName): string
    {
        return implode(array_map('ucfirst', explode('-', $propertyName)));
    }
}
