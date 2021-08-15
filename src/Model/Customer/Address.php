<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 09:03
 */

namespace SasaB\Monri\Model\Customer;

use SasaB\Monri\Arrayable;
use Webmozart\Assert\Assert;

final class Address implements Arrayable
{
    private string $address;
    private string $city;
    private string $zip;
    private string $country;

    /**
     * Address constructor.
     * @param string $address
     * @param string $city
     * @param string $zip
     * @param string $country
     */
    public function __construct(string $address, string $city, string $zip, string $country)
    {
        $this->setAddress($address);
        $this->setCity($city);
        $this->setZip($zip);
        $this->setCountry($country);
    }

    public function asArray(): array
    {
        return [
            'ch_address' => $this->address,
            'ch_city'    => $this->city,
            'ch_zip'     => $this->zip,
            'ch_country' => $this->country
        ];
    }

    public static function fromArray(array $data): self
    {
        [
            'ch_address' => $address,
            'ch_city'    => $city,
            'ch_zip'     => $zip,
            'ch_country' => $country,
        ] = $data;

        return new self($address, $city, $zip, $country);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        array_map(fn ($name) => Assert::alnum($name, 'Invalid city value. Expected alphanumeric. Got: %s'), preg_split('/\s+/', $address));
        Assert::lengthBetween($address, 3, 100, 'Invalid address length. Must be between 3-100 characters');
        $this->address = $address;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        array_map(fn ($name) => Assert::alnum($name, 'Invalid city value. Expected alphanumeric. Got: %s'), preg_split('/\s+/', $city));
        Assert::lengthBetween($city, 3, 30, 'Invalid city length. Must be between 3-30 characters');
        $this->city = $city;
        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        Assert::alnum($zip, 'Invalid zip code value. Expected alphanumeric value. Got: %s');
        Assert::lengthBetween($zip, 3, 9, 'Invalid zip code length. Must be between 3-9 characters');
        $this->zip = $zip;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        Assert::alnum($country, 'Invalid country code value. Expected alpha2, alpha3 letter code or 3 digit ISO numeric code. Got: %s');
        Assert::lengthBetween($country, 2, 3, 'Invalid country code length. Must be between 2-3 characters');
        $this->country = $country;
        return $this;
    }
}
