<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Plugin;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Quote\Model\ResourceModel\Quote\Item\Option\CollectionFactory as QuoteItemOptionCollectionFactory;
use Magento\Sales\Api\Data\OrderItemInterface;
use SwiftOtter\GiftCard\Model\Constants;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class MoveQuoteItemOptionsToOrderItem
{
    /**
     * @var QuoteItemOptionCollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        QuoteItemOptionCollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }
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

        $orderItemOptions = $orderItem->getProductOptions();
        $quoteItemOptions = $this->collectionFactory->create()
            ->addFieldToFilter('item_id', $cartItem->getId());

        foreach ($quoteItemOptions as $quoteItemOption){
            if (!in_array($quoteItemOption->getData('code'), Constants::OPTION_LIST)) {
                continue;
            }

            $orderItemOptions[$quoteItemOption->getData('code')] = $quoteItemOption->getData('value');
        }

        $orderItem->setProductOptions($orderItemOptions);

        return $orderItem;
    }
}
