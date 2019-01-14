<?php
/**
 * Customer Setup
 */
namespace Farmasi\Customer\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * InstallData constructor.
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {

        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '2.2.0', '<')) {
            $this->upgradeVersionTwoTwoZero($customerSetup);
        }

        $setup->endSetup();
    }

    /**
     * @param CustomerSetup $customerSetup
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function upgradeVersionTwoTwoZero($customerSetup)
    {
        $attributeCode = 'profile_picture';

        $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(Customer::ENTITY, $attributeCode, [
            'type'                     => 'varchar',
            'label'                    => 'Photo de profil',
            'input'                    => 'image',
            'required'                 => false,
            'visible'                  => true,
            'user_defined'             => true,
            'sort_order'               => 10,
            'position'                 => 10,
            'system'                   => 0,
            'is_used_in_grid'          => false,
            'is_visible_in_grid'       => false,
            'is_html_allowed_on_front' => true,
            'visible_on_front'         => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, $attributeCode);
        $attribute->addData([
            'attribute_set_id'   => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms'      => ['adminhtml_customer'],
        ]);
        $attribute->save();
    }
}