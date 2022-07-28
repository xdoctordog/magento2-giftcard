<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Plugin;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartExtensionInterface;
use Magento\Quote\Api\Data\CartExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardQuote;

class AssignGiftCardIdToQuote
{
    /**
     * @var CartExtensionInterfaceFactory
     */
    private $cartExtensionFactory;

    /**
     * @var GiftCardQuote
     */
    private $giftCardQuote;

    /**
     * @param CartExtensionInterfaceFactory $cartExtensionFactory
     */
    public function __construct(
        CartExtensionInterfaceFactory $cartExtensionFactory,
        GiftCardQuote $giftCardQuote
    ) {
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->giftCardQuote = $giftCardQuote;
    }

    public function afterGet(
        CartRepositoryInterface $subject,
        CartInterface $cart
    ) {
        $this->loadExtensionAttributes($cart);
        return $cart;
    }

    public function afterGetForCustomer(
        CartRepositoryInterface $subject,
        CartInterface $cart
    ) {
        $this->loadExtensionAttributes($cart);
        return $cart;
    }

    public function afterGetActiveForCustomer(
        CartRepositoryInterface $subject,
        CartInterface $cart
    ) {
        $this->loadExtensionAttributes($cart);
        return $cart;
    }

    public function afterGetActive(
        CartRepositoryInterface $subject,
        CartInterface $cart
    ) {
        $this->loadExtensionAttributes($cart);
        return $cart;
    }

    public function afterGetList(
        CartRepositoryInterface $subject,
        CartSearchResultsInterface $cartSearchResults
    ) {
        foreach ($cartSearchResults->getItems() as $cart) {
            $this->loadExtensionAttributes($cart);
        }

        return $cartSearchResults;
    }

    public function afterSave(CartRepositoryInterface $subject, $result, CartInterface $cart)
    {
        if(!$cartId = $cart->getId()) {
            return $result;
        }
        $giftCardId = $cart->getExtensionAttributes()->getGiftCardId() ?: null;
        $this->giftCardQuote->add((int)$cartId, $giftCardId ? (int)$giftCardId : null);
    }

    private function loadExtensionAttributes(CartInterface $cart): void {
        $extensionAttributes = $cart->getExtensionAttributes() ?: $this->cartExtensionFactory->create();

        if($extensionAttributes->getGiftCardId()) {
           return;
        }

        $giftCardId = $this->giftCardQuote->get((int)$cart->getId());
        $extensionAttributes->setGiftCardId($giftCardId);

        $cart->setExtensionAttributes($extensionAttributes);
    }
}
