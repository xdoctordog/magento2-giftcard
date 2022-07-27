<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Data\CustomerSecure;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;

/**
 * Customer email notification
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EmailNotification
{
    /**#@+
     * Configuration paths for email templates and identities
     */
    const XML_PATH_EMAIL_TEMPLATE = 'catalog/giftcard/email_template';

    const XML_PATH_EMAIL_IDENTITY = 'catalog/giftcard/email_identity';

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Emulation
     */
    private $emulation;

    /**
     * @var UrlInterface
     */
    private $urlInterface;

    /**
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $urlInterface
     * @param Emulation|null $emulation
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        UrlInterface $urlInterface,
        Emulation $emulation = null
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->emulation = $emulation ?? ObjectManager::getInstance()->get(Emulation::class);
        $this->urlInterface = $urlInterface;
    }

    /**
     * Send corresponding email template
     *
     * @param GiftCardInterface $giftCard
     * @param $storeId
     *
     * @return void
     *
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    private function sendEmailTemplate( GiftCardInterface $giftCard, ?int $storeId = null ): void {
        try {
            $this->emulation->startEnvironmentEmulation($storeId);
            $templateId = $this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE, $storeId);

            /** @var array $from */
            $from = $this->scopeConfig->getValue(self::XML_PATH_EMAIL_IDENTITY, ScopeInterface::SCOPE_STORE, $storeId);

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(
                    [
                        'area' => 'frontend',
                        'store' => $storeId
                    ]
                )
                ->setTemplateVars([
                    'url' => $this->urlInterface->getUrl('giftcard/' . $giftCard->getCode()),
                    'giftCard' => $giftCard,
                ])
                ->setFromByScope($from, $storeId)
                ->addTo($giftCard->getRecipientEmail(), $giftCard->getRecipientName())
                ->getTransport();
            $transport->sendMessage();
        } finally {
            $this->emulation->stopEnvironmentEmulation();
        }
    }
}
