<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\GiftCard\Model\Constants;

class CartDetails implements ArgumentInterface
{
    public function getCardDetails(QuoteItem &$quoteItem)
    {
        $codes = [
            (string)__('Recipient name') => Constants::OPTION_RECIPIENT_NAME,
            (string)__('Recipient email') => Constants::OPTION_RECIPIENT_EMAIL
        ];

        $output = array_map(function ($value) use ($quoteItem) {
            return $quoteItem->getOptionByCode($value) ?
            $quoteItem->getOptionByCode($value)->getValue() : '';
        }, $codes);

//        $output  = [
//            (string)__('Recipient name') => $name,
//            (string)__('Recipient email') => $email,
//        ];

        return array_filter($output);
    }
}
