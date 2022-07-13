<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\Quote\Address as QuoteAddress;
use SwiftOtter\GiftCard\Model\Constants as Constants;
use SwiftOtter\GiftCard\Model\Type\GiftCard as GiftCard;

class SetGiftCardCustomPrice implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var QuoteInterface $quote */
        $quote = $observer->getData('quote');
        $adresses = $quote->getAllAddresses();

        if(!$adresses){
            return;
        }

        /**
         * @var QuoteAddress $adress
         */
        foreach ($adresses as $adress) {
            $allItems = $adress->getAllItems();
            /**
             * @var QuoteItem $quoteItem
             */
            foreach ($allItems as $quoteItem) {
                if (
                    $quoteItem->getProductType() !== GiftCard::TYPE_CODE
                    || !$quoteItem->getOptionByCode(Constants::OPTION_AMOUNT)
                    || !($value = $quoteItem->getOptionByCode(Constants::OPTION_AMOUNT)->getValue())
                )
                {
                    continue;
                }
                $quoteItem->setCustomPrice($value);
                $quoteItem->setOriginalCustomPrice($value);
            }
        }
    }
}
