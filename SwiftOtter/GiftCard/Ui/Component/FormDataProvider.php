<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Ui\Component;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider as ModifierPoolDataProvider;
use SwiftOtter\GiftCard\Model\GiftCard;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory as GiftCardCollectionFactory;

class FormDataProvider extends ModifierPoolDataProvider
{
    /**
     * @var \Magento\Cms\Model\ResourceModel\Block\Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        GiftCardCollectionFactory $giftCardCollectionFactory,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $giftCardCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        /** @var GiftCard $giftcard */
        foreach ($items as $giftcard) {
            $this->loadedData[$giftcard->getId()] = $giftcard->getData();
        }

        return $this->loadedData;
    }
}
