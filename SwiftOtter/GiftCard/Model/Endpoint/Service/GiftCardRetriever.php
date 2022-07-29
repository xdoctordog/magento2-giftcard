<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\Endpoint\Service;

use Magento\Checkout\Api\Data\PaymentDetailsInterface;
use Magento\Framework\Exception\StateException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magento\Checkout\Model\PaymentDetailsFactory;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use SwiftOtter\GiftCard\Api\GiftCardRetrieverInterface;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;
use SwiftOtter\GiftCard\Model\GiftCard as GiftCardModel;


class GiftCardRetriever implements GiftCardRetrieverInterface
{
    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var GiftCardRepository
     */
    private $giftCardRepository;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var PaymentMethodManagementInterface
     */
    protected $paymentMethodManagement;

    /**
     * @var PaymentDetailsFactory
     */
    protected $paymentDetailsFactory;

    /**
     * @var CartTotalRepositoryInterface
     */
    protected $cartTotalsRepository;

    /**
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param GiftCardRepository $giftCardRepository
     * @param CartRepositoryInterface $cartRepository
     * @param PaymentMethodManagementInterface $paymentMethodManagement
     * @param PaymentDetailsFactory $paymentDetailsFactory
     * @param CartTotalRepositoryInterface $cartTotalsRepository
     */
    public function __construct(
        QuoteIdMaskFactory $quoteIdMaskFactory,
        GiftCardRepository $giftCardRepository,
        CartRepositoryInterface $cartRepository,
        PaymentMethodManagementInterface $paymentMethodManagement,
        PaymentDetailsFactory $paymentDetailsFactory,
        CartTotalRepositoryInterface $cartTotalsRepository
    ) {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->giftCardRepository = $giftCardRepository;
        $this->cartRepository = $cartRepository;
        $this->paymentMethodManagement = $paymentMethodManagement;
        $this->paymentDetailsFactory = $paymentDetailsFactory;
        $this->cartTotalsRepository = $cartTotalsRepository;
    }

    public function applyGuest(string $maskedId, string $giftCardCode): PaymentDetailsInterface
    {
        /** @var QuoteIdMask $quoteIdMask **/
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($maskedId, 'masked_id');
        $quoteId = (int)$quoteIdMask->getQuoteId();

        return $this->applyCustomer($quoteId, $giftCardCode);
    }

    public function applyCustomer(int $quoteId, string $giftCardCode): PaymentDetailsInterface
    {
        $giftCard = $this->giftCardRepository->getByCode($giftCardCode);

        if($giftCard->getStatus() === GiftCardModel::STATUS_USED
            || $giftCard->getCurrentValue() <= 0
        ) {
            throw new StateException(__('This gift card has already been used. Please choose another one.'));
        }

        $cart = $this->cartRepository->get($quoteId);
        $cart->getExtensionAttributes()->setGiftCardId($giftCard->getId());

        $this->cartRepository->save($cart);

        /** @var PaymentDetailsInterface $paymentDetails */
        $paymentDetails = $this->paymentDetailsFactory->create();
        $paymentDetails->setPaymentMethods($this->paymentMethodManagement->getList($quoteId));
        $paymentDetails->setTotals($this->cartTotalsRepository->get($quoteId));
        return $paymentDetails;
    }
}
