<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;

interface GiftCardSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get gift card list.
     *
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface[]
     */
    public function getItems();

    /**
     * Set gift card list.
     *
     * @param \SwiftOtter\GiftCard\Api\Data\GiftCardInterface[] $items
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface
     */
    public function setItems(array $items);
}
