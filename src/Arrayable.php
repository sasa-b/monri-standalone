<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 12:56
 */

namespace SasaB\Monri;

interface Arrayable
{
    public function asArray(): array;

    public static function fromArray(array $data): self;
}
