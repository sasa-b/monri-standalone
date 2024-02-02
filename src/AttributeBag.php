<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 13:52
 */

declare(strict_types=1);

namespace Sco\Monri;

abstract class AttributeBag implements \ArrayAccess, \IteratorAggregate, \Countable, \JsonSerializable
{
    protected static bool $softUnset = false;

    public function __construct(protected array $attributes = []) {}

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->offsetGet($key) ?? $default;
    }

    public function set(string $key, mixed $value): AttributeBag
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function has(string $key): bool
    {
        return $this->offsetExists($key);
    }

    public function remove(string $key): AttributeBag
    {
        $this->offsetUnset($key);
        return $this;
    }

    public function merge(array $attributes): AttributeBag
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->attributes);
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->attributes);
    }

    public function offsetGet($offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset !== null) {
            $this->attributes[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        if (self::$softUnset && $this->offsetExists($offset)) {
            $this->offsetSet($offset, null);
        } else {
            unset($this->attributes[$offset]);
        }
    }

    public function __set(string $key, mixed $value): void
    {
        $this->offsetSet($key, $value);
    }

    public function __isset(string $key): bool
    {
        return $this->offsetExists($key);
    }

    public function __get(string $key): mixed
    {
        return $this->offsetGet($key);
    }

    public function __unset(string $key): void
    {
        $this->offsetUnset($key);
    }

    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    public function __toString(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
