<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Plugin;

use Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface as GetProductTypesBySkus;
use Magento\InventorySalesApi\Api\Data\ProductSalableResultInterfaceFactory as ProductSalableResultFactory;
use Magento\InventorySalesApi\Api\Data\ProductSalableResultInterface;
use Magento\InventorySales\Model\IsProductSalableForRequestedQtyCondition\IsProductSalableForRequestedQtyConditionChain;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class PreventInventoryForGiftCard
{
    /**
     * @var ProductSalableResultInterfaceFactory
     */
    private $productSalableResultFactory;

    /**
     * @var GetProductTypesBySkus
     */
    private $getProductTypesBySkus;

    public function __construct(
        ProductSalableResultFactory $productSalableResultFactory,
        GetProductTypesBySkus $getProductTypesBySkus
    )
    {
        $this->productSalableResultFactory = $productSalableResultFactory;
        $this->getProductTypesBySkus = $getProductTypesBySkus;
    }

    public function aroundExecute(
        IsProductSalableForRequestedQtyConditionChain $subject,
        callable $proceed,
        string $sku,
        ...$args
    ): ProductSalableResultInterface
    {
        if($this->getProductTypesBySkus->execute([$sku])[$sku] === GiftCard::TYPE_CODE) {
            return $this->productSalableResultFactory->create(['errors' => []]);
        }

        return $proceed($sku, ...$args);
    }
}
