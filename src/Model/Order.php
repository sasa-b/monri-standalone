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
use SasaB\Monri\Model\Order\Amount;
use SasaB\Monri\Model\Order\Currency;
use SasaB\Monri\Model\Order\OrderInfo;
use SasaB\Monri\Model\Order\OrderNumber;
use Webmozart\Assert\Assert;

final class Order implements Arrayable
{
    private OrderInfo $info;
    private OrderNumber $number;
    protected Amount $amount;
    private Currency $currency;

    /**
     * Order constructor.
     * @param OrderInfo $info
     * @param OrderNumber $number
     * @param Amount $amount
     * @param Currency $currency
     */
    public function __construct(
        OrderInfo $info,
        OrderNumber $number,
        Amount $amount,
        Currency $currency
    ) {
        $this->info = $info;
        $this->number = $number;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function fromArray(array $data): self
    {
        [
            'order_info'   => $info,
            'order_number' => $number,
            'amount'       => $amount,
            'currency'     => $currency
        ] = $data;

        return new self(
            new OrderInfo($info),
            new OrderNumber($number),
            new Amount($amount),
            new Currency($currency)
        );
    }

    public function asArray(): array
    {
        return [
            'order_info'   => $this->info,
            'order_number' => $this->number,
            'amount'       => $this->amount,
            'currency'     => $this->currency
        ];
    }

    public function getInfo(): OrderInfo
    {
        return $this->info;
    }

    public function setInfo(OrderInfo $info): self
    {
        $this->info = $info;
        return $this;
    }

    public function getNumber(): OrderNumber
    {
        return $this->number;
    }

    public function setNumber(OrderNumber $number): self
    {
        $this->number = $number;
        return $this;
    }
}
