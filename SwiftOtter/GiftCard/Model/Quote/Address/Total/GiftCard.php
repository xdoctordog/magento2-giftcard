<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\Quote\Address\Total;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote as Quote;
use Magento\Quote\Model\Quote\Address\Total as AddressTotal;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use SwiftOtter\GiftCard\Model\Constants;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;

class GiftCard extends AbstractTotal
{

    /**
     * @var GiftCardRepository
     */
    private $giftCardRepository;

    public function __construct(
        GiftCardRepository $giftCardRepository
    )
    {
        $this->giftCardRepository = $giftCardRepository;
    }

    public function fetch(Quote $quote, AddressTotal $addressTotal)
    {
        $code = $this->getCode();
        return [
            'code' => $code,
            'title' => $this->getLabel(),
            'value' => $addressTotal->getData($code . '_amount')
        ];
    }

    public function collect(
        Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        AddressTotal $total
    ) {
        $giftCardId = $quote->getExtensionAttributes()->getGiftCardId();
        if(!$giftCardId
         || $shippingAssignment->getShipping()->getAddress()->getData('address_type') !== 'shipping') {
            return $this;
        }

        try {
            $giftCard = $this->giftCardRepository->getById((int)$giftCardId);
        } catch (NoSuchEntityException $exception) {
            return $this;
        }

        $giftCardCurrentValue = $giftCard->getCurrentValue();
        if($giftCardCurrentValue <= 0) {
            return $this;
        }

        $giftCardAmount = 0 - min($giftCardCurrentValue, array_sum($total->getAllTotalAmounts()));
        $baseGiftCardAmount = 0 - min($giftCardCurrentValue, array_sum($total->getAllBaseTotalAmounts()));

        $total->addTotalAmount($this->getCode(), $giftCardAmount);
        $total->addBaseTotalAmount($this->getCode(), $baseGiftCardAmount);

        $quote->setData($this->getCode() . '_amount', $giftCardAmount);
        $quote->setData('base_' . $this->getCode() . '_amount', $baseGiftCardAmount);

        return $this;
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Gift Card');
    }

    public function getCode()
    {
        return Constants::TOTAL_NAME;
    }

}
