<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class GiftCard extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('gift_card', 'id');
    }
}
