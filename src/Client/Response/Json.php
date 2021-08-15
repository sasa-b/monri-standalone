<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:24
 */

namespace SasaB\Monri\Client\Response;


use SasaB\Monri\Arrayable;
use SasaB\Monri\AttributeBag;

final class Json extends AttributeBag implements Arrayable
{
    public function asArray(): array
    {
        return $this->attributes;
    }

    public static function fromArray(array $data): Arrayable
    {
        return new self($data);
    }
}
