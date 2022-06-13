<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\Repository;

use SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageSearchResultsInterface;
use SwiftOtter\GiftCard\Model\GiftCardUsageFactory;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage as GiftCardUsageResourceModel;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage\CollectionFactory as GiftCardUsageCollectionFactory;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageSearchResultsInterfaceFactory as SearchResultsFactory;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class GiftCardUsageRepository
{
    /**
     * @var GiftCardUsageResourceModel
     */
    protected $resource;

    /**
     * @var GiftCardUsageFactory
     */
    protected $modelFactory;

    /**
     * @var GiftCardUsageCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var GiftCardUsageSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * GiftCardUsageRepository constructor.
     *
     * @param GiftCardUsageResourceModel $resource
     * @param GiftCardUsageFactory $giftCardFactory
     * @param GiftCardUsageCollectionFactory $giftCardCollectionFactory
     * @param GiftCardUsageSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        GiftCardUsageResourceModel $resource,
        GiftCardUsageFactory $giftCardUsageFactory,
        GiftCardUsageCollectionFactory $giftCardUsageCollectionFactory,
        GiftCardUsageSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->modelFactory = $giftCardUsageFactory;
        $this->collectionFactory = $giftCardUsageCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param GiftCardUsageInterface $giftCardUsage
     *
     * @return GiftCardUsageInterface
     * @throws CouldNotSaveException
     */
    public function save(GiftCardUsageInterface $giftCardUsage)
    {
        try {
            $this->resource->save($giftCardUsage);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $giftCardUsage;
    }

    /**
     * Load gift card usage by given ID
     *
     * @param $giftCardUsageId
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById(int $giftCardUsageId)
    {
        $giftCardUsage = $this->blockFactory->create();
        $this->resource->load($giftCardUsage, $giftCardUsageId);
        if (!$giftCardUsage->getId()) {
            throw new NoSuchEntityException(__('The gift card with the "%1" ID doesn\'t exist.', $giftCardUsageId));
        }
        return $giftCardUsage;
    }

    /**
     * Load gift card usage by given code
     *
     * @param $code
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getByCode(string $code)
    {
        $giftCardUsage = $this->blockFactory->create();
        $this->resource->load($giftCardUsage, $code, 'code');
        if (!$giftCardUsage->getId()) {
            throw new NoSuchEntityException(__('The gift card usage with the code "%1" doesn\'t exist.', $code));
        }
        return $giftCardUsage;
    }

    /**
     * @param SearchCriteriaInterface $criteria
     *
     * @return GiftCardUsageSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var GiftCardUsageSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete gift card usage
     *
     * @param GiftCardUsageInterface $giftCardUsage
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(GiftCardUsageInterface $giftCardUsage): bool
    {
        try {
            $this->resource->delete($giftCardUsage);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete gift card usage by ID
     *
     * @param $giftCardUsageId
     *
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($giftCardUsageId): bool
    {
        return $this->delete($this->getById($giftCardUsageId));
    }

}
