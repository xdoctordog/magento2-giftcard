<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Plugin;

use Magento\Quote\Api\Data\CartExtensionInterfaceFactory;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;

class EnsureQuoteExtensionAttributesExists
{
    /**
     * @var CartExtensionInterfaceFactory
     */
    private $cartExtensionFactory;

    public function __construct(
        CartExtensionInterfaceFactory $cartExtensionFactory
    ) {
        $this->cartExtensionFactory = $cartExtensionFactory;
    }

    public function afterCreate(
        QuoteFactory $subject,
        Quote $quote
    ) {
        $quote->setExtensionAttributes(
            $quote->getExtensionAttributes() ?: $this->cartExtensionFactory->create()
        );

        return $quote;
    }
}
