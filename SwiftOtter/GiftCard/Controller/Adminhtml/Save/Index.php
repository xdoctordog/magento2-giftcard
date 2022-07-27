<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Controller\Adminhtml\Save;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

use SwiftOtter\GiftCard\Model\GiftCardFactory;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CodeGenerator;

class Index extends BackendAction implements HttpPostActionInterface
{
    /**
     * @var GiftCardFactory
     */
    private $giftCardFactory;

    /**
     * @var GiftCardRepository
     */
    private $giftCardRepository;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    public function __construct(
        Context $context,
        GiftCardFactory $giftCardFactory,
        GiftCardRepository $giftCardRepository,
        RedirectFactory $redirectFactory,
        CodeGenerator $codeGenerator
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->giftCardFactory = $giftCardFactory;
        $this->redirectFactory = $redirectFactory;
        $this->codeGenerator = $codeGenerator;

        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getPostValue();
        unset($params['id']);

        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $giftCard = $this->giftCardRepository->getById((int)$id);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('No giftcard was found'));
                $resultRedirect->setPath('*/edit/');

                return $resultRedirect;
            }
        } else {
            $giftCard = $this->giftCardFactory->create();
        }

        if (
            !$giftCard->getInitialValue()
            && isset($params['current_value'])
            && $params['current_value']
        ) {
            $params['initial_value'] = $params['current_value'];
        }

        $giftCard->addData($params);

        if (!$giftCard->getCode()) {
            $giftCard->setCode($this->codeGenerator->getNewCode());
        }

        $this->giftCardRepository->save($giftCard);

        $this->messageManager->addSuccessMessage(__('Gift Card was saved'));

        $resultRedirect->setPath('*/view');

        return $resultRedirect;
    }
}
