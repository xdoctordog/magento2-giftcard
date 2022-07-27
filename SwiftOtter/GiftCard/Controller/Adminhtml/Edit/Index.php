<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Controller\Adminhtml\Edit;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends BackendAction implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        $page = $this->pageFactory->create();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Gift Cards'));

        return $page;
    }
}
