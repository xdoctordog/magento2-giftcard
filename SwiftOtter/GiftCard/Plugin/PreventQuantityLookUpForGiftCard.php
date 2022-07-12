<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Plugin;

use Magento\CatalogInventory\Model\Quote\Item\QuantityValidator;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\Quote\Item;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class PreventQuantityLookUpForGiftCard
{
    public function aroundValidate(
        QuantityValidator $subject,
        callable $proceed,
        Observer $observer
    ) {
        /* @var $quoteItem Item */
        $quoteItem = $observer->getEvent()->getItem();
        if (!$quoteItem ||
            !$quoteItem->getProductId() ||
            !$quoteItem->getQuote()
        ) {
            return;
        }

        $product = $quoteItem->getProduct();

        if($product->getTypeId() === GiftCard::TYPE_CODE) {
            return;
        }

        return $proceed($observer);
    }
}
