<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\Type;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type\Virtual;
use Magento\Framework\DataObject;
use SwiftOtter\GiftCard\Model\Constants as GiftCardConstants;

class GiftCard extends Virtual
{
    const TYPE_CODE = 'ggiftcard';

    /**
     * Check is product available for sale
     *
     * @param Product $product
     * @return bool
     */
    public function isSalable($product): bool
    {
        $salable = $product->getStatus() == Status::STATUS_ENABLED;
        $product->setData('is_salable', $salable);

        return (bool)(int)$salable;
    }

    /**
     * Initialize product(s) for add to cart process.
     *
     * Advanced version of func to prepare product for cart - processMode can be specified there.
     *
     * @param \Magento\Framework\DataObject $buyRequest
     * @param \Magento\Catalog\Model\Product $product
     * @param null|string $processMode
     * @return array|string
     */
    public function prepareForCartAdvanced(\Magento\Framework\DataObject $buyRequest, $product, $processMode = null)
    {
        $products = parent::prepareForCartAdvanced($buyRequest, $product, $processMode);

        if (is_string($products)) {
            return $products;
        }

        if (is_array($products)) {
            $this->assignProductOptions($products, $buyRequest, $product);
        }

        return $products;
    }

    /**
     * @param array $products
     * @param DataObject $buyRequest
     */
    public function assignProductOptions(
        array $products,
        DataObject $buyRequest
    ): void {

        /**
         * @var Product $product
         */
        foreach ($products as $product) {
            foreach (GiftCardConstants::OPTION_LIST as $option) {
                $value = $buyRequest->getData($option);
                if (!$value) {
                    continue;
                }
                $product->addCustomOption($option, $value);
            }
        }
    }
}
