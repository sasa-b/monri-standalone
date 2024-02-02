<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:24
 */

namespace Sco\Monri\Client\Response;

use Sco\Monri\Arrayable;
use Sco\Monri\AttributeBag;

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
