<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\ViewModel\Frontend;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;

class DetailsView implements ArgumentInterface
{
    /**
     * @var GiftCardRepository
     */
    private $giftCardRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        GiftCardRepository $giftCardRepository,
        RequestInterface $request
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->request = $request;
    }

    public function getGiftCard()
    {
        $giftCardId = (int)$this->request->getParam('id');
        return $this->giftCardRepository->getById($giftCardId);
    }
}
