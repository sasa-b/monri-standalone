<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:24
 */

declare(strict_types=1);

namespace Sco\Monri\Client\Response;

use Sco\Monri\Client\Request;
use Sco\Monri\Client\Response;
use Sco\Monri\Client\Serializer;

/**
 * Class Xml
 * @package Sco\Monri\Client\Response
 *
 * <?xml version="1.0" encoding="UTF-8"?>
 * <transaction>
 *  <id type="integer">845</id>
 *  <acquirer>rogach bank</acquirer>
 *  <order-number>abcdef</order-number>
 *  <amount type="integer">54321</amount>
 *  <response-code>000</response-code>
 *  <approval-code>38860</approval-code>
 *  <response-message>authorization OK</response-message>
 *  <reference-number>898951263</reference-number>
 *  <systan>83704</systan>
 *  <cc-type>visa</cc-type>
 *  <status>approved</status>
 *  <transaction-type>capture</transaction-type>
 *  <created-at type="datetime">2011-10-25T03:18:38+02:00</created-at>
 * </transaction>
 */
final class Xml implements Response
{
    private int $id;
    private string $acquirer;
    private string $orderNumber;
    private int $amount;
    private string $responseCode;
    private string $approvalCode;
    private string $responseMessage;
    private string $referenceNumber;
    private string $systan;
    private string $ccType;
    private string $status;
    private string $transactionType;
    private \DateTimeImmutable $createdAt;

    private ?Request $request = null;

    private Serializer $serializer;

    private function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function new(): Xml
    {
        return new self(new Serializer());
    }

    public static function fromString(string $xml): Xml
    {
        return self::new()->deserialize($xml);
    }

    public function serialize(): string
    {
        return $this->serializer->serializeXml($this, ['xml_root_node_name' => 'transaction']);
    }

    public function deserialize(string $data): self
    {
        return $this->serializer->deserializeXml($data, ['xml_root_node_name' => 'transaction']);
    }

    public function getBody(): array
    {
        return [
            'id'               => $this->id,
            'acquirer'         => $this->acquirer,
            'order_number'     => $this->orderNumber,
            'amount'           => $this->amount,
            'response_code'    => $this->responseCode,
            'approval_code'    => $this->approvalCode,
            'response_message' => $this->responseMessage,
            'reference_number' => $this->referenceNumber,
            'systan'           => $this->systan,
            'cc_type'          => $this->ccType,
            'status'           => $this->status,
            'transaction_type' => $this->transactionType,
            'created_at'       => $this->createdAt,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     */
    public function setId($id): void
    {
        $this->id = (int) $id;
    }

    public function getAcquirer(): string
    {
        return $this->acquirer;
    }

    public function setAcquirer(string $acquirer): void
    {
        $this->acquirer = $acquirer;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int|string $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = (int) $amount;
    }

    public function getResponseCode(): string
    {
        return $this->responseCode;
    }

    public function setResponseCode(string $responseCode): void
    {
        $this->responseCode = $responseCode;
    }

    public function getApprovalCode(): string
    {
        return $this->approvalCode;
    }

    public function setApprovalCode(string $approvalCode): void
    {
        $this->approvalCode = $approvalCode;
    }

    public function getResponseMessage(): string
    {
        return $this->responseMessage;
    }

    public function setResponseMessage(string $responseMessage): void
    {
        $this->responseMessage = $responseMessage;
    }

    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber(string $referenceNumber): void
    {
        $this->referenceNumber = $referenceNumber;
    }

    public function getSystan(): string
    {
        return $this->systan;
    }

    public function setSystan(string $systan): void
    {
        $this->systan = $systan;
    }

    public function getCcType(): string
    {
        return $this->ccType;
    }

    public function setCcType(string $ccType): void
    {
        $this->ccType = $ccType;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    public function setTransactionType(string $transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable|string $createdAt): void
    {
        if (is_string($createdAt)) {
            $dateTime = \DateTimeImmutable::createFromFormat(DATE_ATOM, $createdAt);
            if ($dateTime === false) {
                throw new \InvalidArgumentException(\sprintf('Invalid date format %s, expected %s', $createdAt, DATE_ATOM));
            }
            $this->createdAt = $dateTime;
        } else {
            $this->createdAt = $createdAt;
        }
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function forRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }
}
