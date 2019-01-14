<?php
/**
 * Customer Setup
 */
namespace Magento\Customer\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    
        if (version_compare($context->getVersion(), '2.2.1', '<')) {
            $this->upgradeCustomerPatent($customerSetup);
        }

        $setup->endSetup();
    }

    /**
     * @param CustomerSetup $customerSetup
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function upgradeCustomerPatent($customerSetup)
    {
        $attributeCode = 'customer_patent';
        $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(Customer::ENTITY, $attributeCode, [
            'type' => 'int',
            'label' => 'Patente',
            'input' => 'select',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'position' => 999,
            'system' => 0,
            'default' => 0,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_html_allowed_on_front' => false,
            'visible_on_front' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, $attributeCode);
        $attribute->addData([
            'attribute_set_id'   => $attributeSetId,
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms'      => ['adminhtml_customer'],
            'used_in_forms' => ['adminhtml_customer', 'customer_account_edit'],
        ]);
        $attribute->save();
    }
