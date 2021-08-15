<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 12:55
 */

declare(strict_types=1);

namespace SasaB\Monri\Model;

use SasaB\Monri\Arrayable;
use SasaB\Monri\Model\Customer\Address;
use SasaB\Monri\Model\Customer\Email;
use SasaB\Monri\Model\Customer\FullName;
use SasaB\Monri\Model\Customer\Phone;


final class Customer implements Arrayable
{
    private FullName $fullName;
    private Email $email;
    private Phone $phone;
    private Address $address;

    /**
     * Customer constructor.
     * @param FullName $fullName
     * @param Email $email
     * @param Phone $phone
     * @param Address $address
     */
    public function __construct(
        FullName $fullName,
        Email $email,
        Phone $phone,
        Address $address
    ) {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
    }

    public static function fromArray(array $data): self
    {
        [
            'ch_full_name' => $fullName,
            'ch_email'     => $email,
            'ch_phone'     => $phone,
            'ch_address'   => $address,
            'ch_city'      => $city,
            'ch_zip'       => $zip,
            'ch_country'   => $country
        ] = $data;

        return new self(
            new FullName($fullName),
            new Email($email),
            new Phone($phone),
            new Address($address, $city, $zip, $country)
        );
    }

    public function asArray(): array
    {
        return array_merge([
            'ch_full_name' => $this->fullName->value(),
            'ch_email'     => $this->email->value(),
            'ch_phone'     => $this->phone->value(),
        ], $this->address->asArray());
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone): void
    {
        $this->phone = $phone;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }
}
