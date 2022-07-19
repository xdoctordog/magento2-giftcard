<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model;

use Magento\Framework\Model\AbstractModel;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard as GiftCardResourceModel;

class GiftCard extends AbstractModel implements GiftCardInterface
{
    public const STATUS_ACTIVE = 1;

    public const STATUS_USED = 2;

    protected $_eventPrefix = 'gift_card';

    protected function _construct() {
        $this->_init(GiftCardResourceModel::class);
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->getData('id');
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
    public function getAssignedCustomerId(): int
    {
        return (int)$this->getData('assigned_customer_id');
    }

    /**
     * @param int $assignedCustomerId
     * @return void
     */
    public function setAssignedCustomerId(int $assignedCustomerId): void
    {
        $this->setData('assigned_customer_id', $assignedCustomerId);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this->getData('code');
    }

    /**
     * @param string $code
     * @return void
     */
    public function setCode(string $code): void
    {
        $this->setData('code', $code);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return (int)$this->getData('status');
    }

    /**
     * @param int $status
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this->setData('status', $status);
    }

    /**
     * @return float
     */
    public function getInitialValue(): float
    {
        return (float)$this->getData('initial_value');
    }

    /**
     * @param float $initialValue
     * @return void
     */
    public function setInitialValue(float $initialValue): void
    {
        $this->setData('initial_value', $initialValue);
    }

    /**
     * @return float
     */
    public function getCurrentValue(): float
    {
        return (float)$this->getData('current_value');
    }

    /**
     * @param float $currentValue
     * @return void
     */
    public function setCurrentValue(float $currentValue): void
    {
        $this->setData('current_value', $currentValue);
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

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getUpdatedAt(): \DateTime
    {
        return (new \DateTime($this->getData('updated_at')));
    }

    /**
     * @param \DateTime $updatedAt
     * @return void
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->setData('updated_at', $updatedAt-format('Y-m-d h:i:s'));
    }

    /**
     * @return string
     */
    public function getRecipientEmail(): string
    {
        return (string)$this->getData('recipient_email');
    }

    /**
     * @param string $recipientEmail
     * @return void
     */
    public function setRecipientEmail(string $recipientEmail): void
    {
        $this->setData('recipient_email', $recipientEmail);
    }

    /**
     * @return string
     */
    public function getRecipientName(): string
    {
        return (string)$this->getData('recipient_name');
    }

    /**
     * @param string $recipientName
     * @return void
     */
    public function setRecipientName(string $recipientName): void
    {
        $this->setData('recipient_name', $recipientName);
    }
}
