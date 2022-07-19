<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\Repository;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface;
use SwiftOtter\GiftCard\Model\GiftCardFactory;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard as GiftCardResourceModel;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory as GiftCardCollectionFactory;
//use SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterfaceFactory as SearchResultsFactory;
use Magento\Framework\Api\SearchResultsFactory as SearchResultsFactory;

class GiftCardRepository
{
    /**
     * @var GiftCardResourceModel
     */
    protected $resource;

    /**
     * @var GiftCardFactory
     */
    protected $modelFactory;

    /**
     * @var GiftCardCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var SearchResultsFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        GiftCardResourceModel $resource,
        GiftCardFactory $giftCardFactory,
        GiftCardCollectionFactory $giftCardCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->modelFactory = $giftCardFactory;
        $this->collectionFactory = $giftCardCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param GiftCardInterface $giftCard
     *
     * @return GiftCardInterface
     * @throws CouldNotSaveException
     */
    public function save(GiftCardInterface $giftCard)
    {
        try {
            $this->resource->save($giftCard);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $giftCard;
    }

    /**
     * Load gift card by given ID
     *
     * @param $giftCardId
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById(int $giftCardId): GiftCardInterface
    {
        $giftCard = $this->blockFactory->create();
        $this->resource->load($giftCard, $giftCardId);
        if (!$giftCard->getId()) {
            throw new NoSuchEntityException(__('The gift card with the "%1" ID doesn\'t exist.', $giftCardId));
        }
        return $giftCard;
    }

    /**
     * Load gift carf by given code
     *
     * @param $code
     *
     * @return GiftCardInterface
     * @throws NoSuchEntityException
     */
    public function getByCode(string $code)
    {
        $giftCard = $this->blockFactory->create();
        $this->resource->load($giftCard, $code, 'code');
        if (!$giftCard->getId()) {
            throw new NoSuchEntityException(__('The gift card with the code "%1" doesn\'t exist.', $code));
        }
        return $giftCard;
    }

    /**
     * @param SearchCriteriaInterface $criteria
     *
     * @return GiftCardSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var GiftCardSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete gift card
     *
     * @param GiftCardInterface $giftCard
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(GiftCardInterface $giftCard): bool
    {
        try {
            $this->resource->delete($giftCard);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete gift card by ID
     *
     * @param $giftCardId
     *
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById( $giftCardId): bool
    {
        return $this->delete($this->getById($giftCardId));
    }

}
