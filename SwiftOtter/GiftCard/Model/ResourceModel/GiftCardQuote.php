<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class GiftCardQuote extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('gift_card_quote', 'id');
    }

    public function add(int $quoteId, ?int $giftCardId): void
    {
        $isSet = (bool)$this->get($quoteId);

        if($isSet && $giftCardId) {
            // update
            $this->getConnection()->update(
                $this->getMainTable(),
                [
                    'gift_card_id' => $giftCardId,
                ],
                $this->getConnection()->quoteInto('quote_id = ?', $quoteId)
            );
        } elseif ($isSet && !$giftCardId) {
            $this->getConnection()->delete(
                $this->getMainTable(),
                $this->getConnection()->quoteInto('quote_id = ?', $quoteId)
            );
        } elseif($giftCardId) {
            $this->getConnection()->insert(
                $this->getMainTable(),
                [
                    'gift_card_id' => $giftCardId,
                    'quote_id' => $quoteId,
                ],
            );

        }
    }

    public function get(int $quoteId): ?int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'gift_card_id');
        $select->where('quote_id = ?', $quoteId);

        $value = $this->getConnection()->fetchOne($select);

        return $value ? (int)$value : null;
    }
}
