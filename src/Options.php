<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 14:11
 */

declare(strict_types=1);

namespace SasaB\Monri;


/**
 * Class Options
 * @package SasaB\Monri\Api
 * @property bool $moto
 * @property int $number_of_installments
 * @property bool $tokenize_pan
 * @property bool $tokenize_pan_offered
 * @property string $tokenize_brands
 * @property string $supported_payment_methods
 * @property string $supported_cc_issuers
 * @property string $rules
 * @property bool $force_installments
 * @property string $custom_attributes
 */
final class Options extends AttributeBag implements Arrayable
{
    private const DEFAULT = [
        // Processing data
        'moto'                   => null,
        'number_of_installments' => null,
        // Additional info
        'tokenize_pan'              => null,
        'tokenize_pan_offered'      => null,
        'tokenize_brands'           => null,
        'supported_payment_methods' => null,
        'supported_cc_issuers'      => null,
        'rules'                     => null,
        'force_installments'        => null,
        'custom_attributes'         => null,
    ];

    protected array $attributes = self::DEFAULT;

    public function offsetUnset($offset): void
    {
        if (array_key_exists($offset, $this->attributes)) {
            $this->attributes[$offset] = null;
        }
    }

    public function mergeOptions(Options $options): self
    {
        $this->merge($options->asArray());
        return $this;
    }

    public function asArray(): array
    {
        return array_filter($this->attributes, static fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(array_merge(self::DEFAULT, $data));
    }

    public static function default(): self
    {
        return new self([
            'tokenize_pan_offered'      => true,
            'tokenize_brands'           => 'visa,master,maestro,diners,amex,jcb,discover',
            'supported_payment_methods' => 'card',
        ]);
    }
}
