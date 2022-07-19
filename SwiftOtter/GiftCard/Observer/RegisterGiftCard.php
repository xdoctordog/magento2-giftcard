<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Observer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\InvoiceItemInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use SwiftOtter\GiftCard\Model\Constants;
use SwiftOtter\GiftCard\Model\GiftCard as GiftCardModel;
use SwiftOtter\GiftCard\Model\Type\GiftCard;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;
use SwiftOtter\GiftCard\Model\GiftCardFactory;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CodeGenerator;

class RegisterGiftCard implements ObserverInterface
{
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var ProductInterface[]
     */
    private $productCache = [];

    /**
     * @var OrderItemRepositoryInterface
     */
    private $orderItemRepository;

    /**
     * @var OrderItemInterface[]
     */
    private $orderItemCache = [];

    /**
     * @var GiftCardFactory
     */
    private $giftCardFactory;

    /**
     * @var GiftCardRepository
     */
    private $giftCardRepository;

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    /**
     * RegisterGiftCard constructor.
     *
     * @param ProductCollectionFactory $productCollectionFactory
     */
    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        OrderItemRepositoryInterface $orderItemRepository,
        GiftCardFactory $giftCardFactory,
        GiftCardRepository $giftCardRepository,
        CodeGenerator $codeGenerator
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->orderItemRepository = $orderItemRepository;
        $this->giftCardFactory = $giftCardFactory;
        $this->giftCardRepository = $giftCardRepository;
        $this->codeGenerator = $codeGenerator;
    }

    public function execute(Observer $observer)
    {
        /**
         * @var InvoiceInterface $invoice
         */
        $invoice = $observer->getData('invoice');
        $giftCardInvoiceItems = array_filter(iterator_to_array($invoice->getItems()),
            function(InvoiceItemInterface $invoiceItem) {
            $productId = $invoiceItem->getProductId();
            if(!$productId) {
                return false;
            }
            $product = $this->getProduct((int)$productId);

            return $product->getTypeId() === GiftCard::TYPE_CODE;
        });

        if(!count($giftCardInvoiceItems)){
            return;
        }

        foreach ($giftCardInvoiceItems as $giftCardInvoiceItem){
            $this->createGiftCard($giftCardInvoiceItem);
        }
    }

    private function getOrderItem(InvoiceItemInterface $invoiceItem)
    {
        if(method_exists($invoiceItem, 'getOrderItem')
        && $orderItem = $invoiceItem->getOrderItem()) {
            return $orderItem;
        }

        $orderItemId = $invoiceItem->getOrderItemId();
        if (isset($this->orderItemCache[$orderItemId])) {
            return $this->orderItemCache[$orderItemId];
        }

        $orderItem = $this->orderItemRepository->get($orderItemId);
        $this->orderItemCache[$orderItemId] = $invoiceItem->getOrderItem();

        return $this->orderItemCache[$orderItemId];
    }

    private function createGiftCard(InvoiceItemInterface $invoiceItem): void
    {
        $giftCard = $this->giftCardFactory->create();
        $orderItem = $this->getOrderItem($invoiceItem);

        $recipientName = $orderItem->getProductOptionByCode(Constants::OPTION_RECIPIENT_NAME);
        $recipientEmail = $orderItem->getProductOptionByCode(Constants::OPTION_RECIPIENT_EMAIL);

        if (!$recipientEmail) {
            throw new NoSuchEntityException(__('The recipient\'s email was not set here.'));
        }

        $value = $invoiceItem->getQty() * ($orderItem->getRowTotal() / $orderItem->getQtyOrdered());
        $giftCard->setCurrentValue($value);
        $giftCard->setInitialValue($value);
        $giftCard->setRecipientName((string)$recipientName);
        $giftCard->setRecipientEmail((string)$recipientEmail);
        $giftCard->setCode($this->codeGenerator->getNewCode());
        $giftCard->setStatus(GiftCardModel::STATUS_ACTIVE);
        $giftCard->save();
    }

    private function getProduct(int $productId): ProductInterface
    {
        if(isset($this->productCache[$productId])) {
            return $this->productCache[$productId];
        }

        $collection = $this->productCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', $productId);
        $collection->setPageSize(1);

        $this->productCache[$productId] = $collection->getFirstItem();

        return $this->productCache[$productId];
    }
}
