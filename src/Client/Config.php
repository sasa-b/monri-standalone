<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 17:00
 */

namespace SasaB\Monri\Client;

use SasaB\Monri\Arrayable;
use SasaB\Monri\AttributeBag;

/**
 * Class Config
 * @package SasaB\Monri\Client
 * @property string $url
 * @property string $key
 * @property string $language
 * @property string $authenticity_token
 * @property string $success_url_override
 * @property string $cancel_url_override
 * @property string $callback_url_override
 */
final class Config extends AttributeBag implements Arrayable
{
    private const DEFAULT = [
        'url'      => null,
        'key'      => null,
        // Processing data
        'language' => 'ba',
        'authenticity_token'    => null,
        'success_url_override'  => null,
        'cancel_url_override'   => null,
        'callback_url_override' => null,
    ];

    protected array $attributes = self::DEFAULT;

    public static function loadFromEnv(): self
    {
        $config = new self();
        $config->attributes = [
            'url'      => rtrim(getenv('MONRI_FORM_URL')),
            'key'      => rtrim(getenv('MONRI_KEY')),
            'language' => rtrim(getenv('MONRI_LANGUAGE')),
            'authenticity_token'    => rtrim(getenv('MONRI_TOKEN')),
            'success_url_override'  => rtrim(getenv('MONRI_SUCCESS_URL')),
            'cancel_url_override'   => rtrim(getenv('MONRI_CANCEL_URL')),
            'callback_url_override' => rtrim(getenv('MONRI_CALLBACK_URL')),
        ];
        return $config;
    }

    public static function default(string $token = null, string $key = null, string $url = null): self
    {
        $config = self::loadFromEnv();

        if ($url) {
            $config->url = $url;
        }

        if ($token) {
            $config->authenticity_token = $token;
        }

        if ($key) {
            $config->key = $key;
        }

        return $config;
    }

    public function asArray(): array
    {
        return array_filter($this->attributes, static fn ($value, $key) => !in_array($key, ['url', 'key']) && $value !== null, ARRAY_FILTER_USE_BOTH);
    }

    public static function fromArray(array $data): Arrayable
    {
        return new self(array_merge(self::DEFAULT, $data));
    }

    public function offsetUnset($offset): void
    {
        if (array_key_exists($offset, $this->attributes)) {
            $this->attributes[$offset] = null;
        }
    }

    public function mergeConfig(Config $config): self
    {
        $config->merge($config->asArray());
        return $this;
    }
}
