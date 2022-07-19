<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\ResourceModel\GiftCard;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CodeGenerator extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('gift_card', 'id');
    }

    public function getNewCode(): string
    {
        do {
            $code = $this->generate();

            $select = $this->getConnection()->select();
            $select->from($this->getMainTable(), 'id')
                ->where('code = ?', $code);
            $foundedCode = $this->getConnection()->fetchOne($select);
            $result = $foundedCode ? true : false;
        } while($result);

        return $code;
    }

    private function generate()
    {
        return bin2hex(random_bytes(10));
    }
}
