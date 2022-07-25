<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Controller\Adminhtml;

use Magento\Backend\App\Action as BackendAction;

abstract class GiftCardAbstract extends BackendAction
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'SwiftOtter_GiftCard::management';
}
