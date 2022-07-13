<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Product
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepositoryInterface;
    /**
     * @var RequestInterface
     */
    private $requestInterface;

    /**
     * @param ProductRepositoryInterface $productRepositoryInterface
     * @param RequestInterface $requestInterface
     */
    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        RequestInterface $requestInterface
    )
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->requestInterface = $requestInterface;
    }

    public function get(): ProductInterface
    {
        $productId = $this->requestInterface->getParam('id');
        if (!$productId) {
            throw new NoSuchEntityException(__('Product ID is not specified.'));
        }

        return $this->productRepositoryInterface->getById((int)$productId);
    }
}
