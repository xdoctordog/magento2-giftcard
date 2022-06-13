<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Api\Data;

interface GiftCardUsageInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getGiftCardId(): int;

    /**
     * @param int $giftCardId
     * @return void
     */
    public function setGiftCardId(int $giftCardId): void;

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     * @return void
     */
    public function setOrderId(int $orderId): void;

    /**
     * @return float
     */
    public function getValueChange(): float;

    /**
     * @param float $valueChange
     * @return void
     */
    public function setValueChange(float $valueChange): void;

    /**
     * @return string
     */
    public function getNotes(): string;

    /**
     * @param int $notes
     * @return void
     */
    public function setNotes(float $notes): void;

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @param \DateTime $createdAt
     * @return void
     */
    public function setCreatedAt(\DateTime $createdAt): void;
}
