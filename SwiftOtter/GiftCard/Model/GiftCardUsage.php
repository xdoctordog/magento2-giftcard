<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model;

use Magento\Framework\Model\AbstractModel;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface;

class GiftCardUsage extends AbstractModel implements GiftCardUsageInterface
{
    protected $_eventPrefix = 'gift_card_usage';
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getData('id');
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->setData('id', $id);
    }

    /**
     * @return int
     */
    public function getGiftCardId(): int
    {
        return (int)$this->getData('gift_card_id');
    }

    /**
     * @param int $giftCardId
     * @return void
     */
    public function setGiftCardId(int $giftCardId): void
    {
        $this->setData('gift_card_id', $giftCardId);
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return (int)$this->getData('order_id');
    }

    /**
     * @param int $orderId
     * @return void
     */
    public function setOrderId(int $orderId): void
    {
        $this->setData('order_id', $orderId);
    }

    /**
     * @return float
     */
    public function getValueChange(): float
    {
        return (float)$this->getData('value_change');
    }

    /**
     * @param float $valueChange
     * @return void
     */
    public function setValueChange(float $valueChange): void
    {
        $this->setData('value_change', $valueChange);
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return (string)$this->getData('value_change');
    }

    /**
     * @param int $notes
     * @return void
     */
    public function setNotes(float $notes): void
    {
        $this->setData('notes', $notes);
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getCreatedAt(): \DateTime
    {
        return (new \DateTime($this->getData('created_at')));
    }

    /**
     * @param \DateTime $createdAt
     * @return void
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->setData('created_at', $createdAt-format('Y-m-d h:i:s'));
    }
}
