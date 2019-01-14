<?php

namespace Magento\Catalog\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Data constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(\Magento\Customer\Model\Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    /**
     * {@inheritdoc}
     * @var boolean
     */
    public function hasPatent()
    {
        $hasPatent = $this->customerSession->getCustomer()->getCustomerPatent();
        if ($hasPatent) {
            return true;
        } else {
            return false;
        }
}
