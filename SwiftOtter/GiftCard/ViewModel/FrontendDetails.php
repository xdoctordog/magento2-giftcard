<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\GiftCard\Model\Attributes;
use SwiftOtter\GiftCard\Service\Product as ServiceProduct;

class FrontendDetails implements ArgumentInterface
{
    /**
     * @var SwiftOtter\GiftCard\Service\Product
     */
    private $serviceProduct;

    /**
     * @param ServiceProduct $serviceProduct
     */
    public function __construct(
        ServiceProduct $serviceProduct
    )
    {
        $this->serviceProduct = $serviceProduct;
    }

    public function getIsCustomAmountAllowed(): bool
    {
        return true;//@TODO: Waiting while admin product page field is fixed

        $product = $this->serviceProduct->get();

        if ($value = $product->getData(Attributes::IS_CUSTOM_ALLOWED)) {
            return (bool)$value;
        } elseif ($isCustomAllowed = $product->getCustomAttribute(Attributes::IS_CUSTOM_ALLOWED)) {
            return $isCustomAllowed;
        }

        return false;
    }
}
