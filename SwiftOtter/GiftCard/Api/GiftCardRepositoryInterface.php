<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;

interface GiftCardRepositoryInterface
{
    public function save(GiftCardInterface $giftCard, ?int $storeId = null);

    public function getById(int $giftCardId): GiftCardInterface;

    public function getByCode(string $code);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(GiftCardInterface $giftCard): bool;

    public function deleteById( $giftCardId): bool;
}
