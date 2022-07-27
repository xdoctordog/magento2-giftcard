<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Ui\Component;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return parent::getData();
    }
}
