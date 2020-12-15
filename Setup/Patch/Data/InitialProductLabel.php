<?php

namespace Namluu\ProductLabel\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;

class InitialProductLabel implements DataPatchInterface, PatchVersionInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /**
     * InitialProductLabel constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
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
        $this->moduleDataSetup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $productLabelAttributes = ['onsale' => __('On Sale'), 'feature' => __('Feature')];
        foreach ($productLabelAttributes as $attributeCode => $attributeLabel) {
            if (!$eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode)) {
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attributeCode,
                    [
                        'group' => 'General',
                        'type' => 'int',
                        'backend' => '',
                        'frontend' => '',
                        'label' => $attributeLabel,
                        'input' => 'boolean',
                        'class' => '',
                        'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => false,
                        'default' => '0',
                        'searchable' => false,
                        'filterable' => true,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'used_in_product_listing' => true,
                        'unique' => false,
                    ]
                );
            }
        }
        $this->moduleDataSetup->endSetup();
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

    /**
     * @inheritdoc
     */
    public static function getVersion()
    {
        return '2.0.0';
    }
}
