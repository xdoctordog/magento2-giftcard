<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Checkout\Model\Session\Proxy;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;

class GiftCardCheckoutInitializer implements LayoutProcessorInterface
{
    /**
     * @var \Magento\Checkout\Model\Session\Proxy
     */
    private $session;

    /**
     * @var GiftCardRepository
     */
    private $giftCardRepository;

    public function __construct(
        \Magento\Checkout\Model\Session\Proxy $session,
        GiftCardRepository $giftCardRepository
    )
    {
        $this->session = $session;
        $this->giftCardRepository = $giftCardRepository;
    }

    public function process($jsLayout)
    {
        $quote = $this->session->getQuote();
        $giftCardId = null;

        if(!($extensionAttributes = $quote->getExtensionAttributes())
            || !($giftCardId = $extensionAttributes->getGiftCardId())
        ) {
            return $jsLayout;
        }

        $giftCard = $this->giftCardRepository->getById($giftCardId);

        $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']
            ['children']['itemsAfter']['children']['giftcard']['config']['code'] = $giftCard->getCode();

        return $jsLayout;
    }

}
