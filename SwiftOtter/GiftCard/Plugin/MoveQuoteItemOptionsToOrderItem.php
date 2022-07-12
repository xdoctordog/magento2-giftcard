<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Plugin;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Api\Data\OrderItemInterface;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class MoveQuoteItemOptionsToOrderItem
{
    /**
     * @param ToOrderItem $subject
     * @param OrderItemInterface $orderItem
     * @param QuoteItem $cartItem
     * @param $data
     *
     * @return OrderItemInterface
     */
    public function afterConvert(ToOrderItem $subject, OrderItemInterface $orderItem, $cartItem, $data)
    {
        if ($cartItem->getProductType() !== GiftCard::TYPE_CODE) {
            return $orderItem;
        }

        $orderOptions = $cartItem->getProductOrderOptions();

        return $orderItem;
    }
}
