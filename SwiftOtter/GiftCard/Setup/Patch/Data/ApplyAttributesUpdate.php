<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Setup\Patch\Data;

use SwiftOtter\GiftCard\Model\Type\GiftCard;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

/**
 * Class ApplyAttributesUpdate
 * @package SwiftOtter\GiftCard\Setup\Patch\Data
 */
class ApplyAttributesUpdate implements DataPatchInterface
{
    const PRICE_ATTR = 'price';
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * ApplyAttributesUpdate constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $applyTo = explode(
            ',',
            $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, self::PRICE_ATTR, 'apply_to')
        );
        if (!in_array(GiftCard::TYPE_CODE, $applyTo)) {
            $applyTo[] = GiftCard::TYPE_CODE;
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                self::PRICE_ATTR,
                'apply_to',
                implode(',', $applyTo)
            );
        }
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
