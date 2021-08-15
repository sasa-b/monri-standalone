<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 14:11
 */

declare(strict_types=1);

namespace SasaB\Monri;

use SasaB\Monri\Client\Language;
use SasaB\Monri\Model\CardBrands;

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
        'language'               => Language::BA,
        'transaction_type'       => null,
        'authenticity_token'     => null,
        'number_of_installments' => null,
        'moto'                   => null,
        // Additional info
        'tokenize_pan_offered'      => null,
        'supported_payment_methods' => null,
        'tokenize_pan'              => null,
        'tokenize_brands'           => null,
        'supported_cc_issuers'      => null,
        'rules'                     => null,
        'force_installments'        => null,
        'custom_attributes'         => null,
        'success_url_override'      => null,
        'cancel_url_override'       => null,
        'callback_url_override'     => null,
    ];

    protected array $attributes = self::DEFAULT;

    protected static bool $softUnset = true;

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

    public static function loadFromEnv(): self
    {
        $options = new self();
        $options->attributes = array_merge($options->attributes, [
            'language'              => env('MONRI_LANG', Language::BA),
            'success_url_override'  => env('MONRI_SUCCESS_URL'),
            'cancel_url_override'   => env('MONRI_CANCEL_URL'),
            'callback_url_override' => env('MONRI_CALLBACK_URL'),
        ]);
        return $options;
    }

    public static function default(array $with = []): self
    {
        $options = self::loadFromEnv();
        $options->attributes = array_merge($options->attributes, $with, [
            'tokenize_pan_offered'      => true,
            'tokenize_brands'           => CardBrands::allAsString(),
            'supported_payment_methods' => 'card',
        ]);
        return $options;
    }
}
