<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 12:55
 */

declare(strict_types=1);

namespace Sco\Monri\Model;

use Sco\Monri\Arrayable;
use Sco\Monri\Model\Order\Amount;
use Sco\Monri\Model\Order\Currency;
use Sco\Monri\Model\Order\OrderInfo;
use Sco\Monri\Model\Order\OrderNumber;

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
            'order_info'   => $this->info->value(),
            'order_number' => $this->number->value(),
            'amount'       => $this->amount->value(),
            'currency'     => $this->currency->value()
        ];
    }

    public function getInfo(): OrderInfo
    {
        return $this->info;
    }

    public function setInfo(OrderInfo $info): void
    {
        $this->info = $info;
    }

    public function getNumber(): OrderNumber
    {
        return $this->number;
    }

    public function setNumber(OrderNumber $number): void
    {
        $this->number = $number;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function setAmount(Amount $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }
}
