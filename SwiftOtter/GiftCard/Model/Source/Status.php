<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use SwiftOtter\GiftCard\Model\GiftCard as GiftCard;

class Status implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => GiftCard::STATUS_ACTIVE,
                'label' => __('Active'),
            ],
            [
                'value' => GiftCard::STATUS_USED,
                'label' => __('Used'),
            ],
        ];
    }
}
