<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\ViewModel\Frontend;

use Magento\Customer\Model\Session;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\GiftCard\Api\GiftCardRepositoryInterface;

class View implements ArgumentInterface
{
    public const ASSIGNED_CUSTOMER_ID = 'assigned_customer_id';
    /**
     * @var GiftCardRepositoryInterface
     */
    private $giftCardRepository;

    /**
     * @var SearchCriteriaInterface
     */
    private $searchCriteria;

    /**
     * @var FilterGroup
     */
    private $filterGroup;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var Session
     */
    private $session;

    public function __construct(
        SearchCriteriaInterface $searchCriteria,
        GiftCardRepositoryInterface $giftCardRepository,
        FilterGroup $filterGroup,
        Filter $filter,
        Session $session
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->searchCriteria = $searchCriteria;
        $this->filterGroup = $filterGroup;
        $this->filter = $filter;
        $this->session = $session;
    }

    public function getGiftCards()
    {
        $id = $this->session->getCustomer()->getId();//@TODO: DOES NOT WORK CORRECTLY
        $id = 3;
        $filter =
            $this->filter
                ->setField(self::ASSIGNED_CUSTOMER_ID)
                ->setValue($id);
        $filterGroups = $this->filterGroup->setFilters([$filter]);
        return $this->giftCardRepository->getList($this->searchCriteria->setFilterGroups([$filterGroups]))->getItems();
    }
}
