<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface;

interface GiftCardUsageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get gift card list.
     *
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface[]
     */
    public function getItems();

    /**
     * Set gift card list.
     *
     * @param \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface[] $items
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageSearchResultsInterface
     */
    public function setItems(array $items);
}
