<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\ResourceModel\GiftCard;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\GiftCard\Model\GiftCard as GiftCardModel;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard as GiftCardResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct() {
        $this->_init(GiftCardModel::class, GiftCardResourceModel::class);
    }
}
