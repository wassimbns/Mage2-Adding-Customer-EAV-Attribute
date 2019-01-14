<?php

namespace Farmasi\Catalog\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var float
     */
    public $unpaidAmount;

    /**
     * @var float
     */
    public $penaltyAmount;

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
     * @var float
     */
    public function getUnpaidAmount()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("Magento\Customer\Model\Session");
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $resources = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resources->getConnection();
            $tableName = $resources->getTableName('farmasi_customer_arriere');
            $sql = "SELECT amount FROM {$tableName} WHERE codeSite = {$customerId}";
            $unpaidAmount = $connection->fetchOne($sql);
        }
        if (!empty($unpaidAmount)) {
            return (float)$unpaidAmount;
        } else
            return 0.00;
    }

    /**
     * {@inheritdoc}
     * @var float
     */
    public function getPenaltyAmount()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("Magento\Customer\Model\Session");
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $resources = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resources->getConnection();
            $tableName = $resources->getTableName('farmasi_customer_penalite');
            $sql = "SELECT amount FROM {$tableName} WHERE codeSite = {$customerId}";
            $penaltyAmount = $connection->fetchOne($sql);
        }
        if (!empty($penaltyAmount)) {
            return (float)$penaltyAmount;
        } else
            return 0.00;
    }

    /**
     * {@inheritdoc}
     * @var float
     */
    public function getRefundAmount()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("Magento\Customer\Model\Session");
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $resources = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resources->getConnection();
            $tableName = $resources->getTableName('farmasi_customer_ristroune');
            $sql = "SELECT amount FROM {$tableName} WHERE codeSite = {$customerId} AND year = YEAR(CURRENT_DATE()) AND month = MONTH(CURRENT_DATE())";
            $refundAmount = $connection->fetchOne($sql);
        }
        if (!empty($refundAmount)) {
            return (float)$refundAmount;
        } else
            return 0.00;

    }

    /**
     * {@inheritdoc}
     * @var
     */
    public function resetRefundAmount($refundAmount)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("Magento\Customer\Model\Session");
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $resources = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resources->getConnection();
            $tableName = $resources->getTableName('farmasi_customer_ristroune');
            $sql = "UPDATE {$tableName} SET amount = {$refundAmount} WHERE codeSite = {$customerId}";
            $connection->query($sql);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     * @var
     */
    public function resetPenaltyAmount()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("Magento\Customer\Model\Session");
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();

            $resources = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resources->getConnection();
            $tableName = $resources->getTableName('farmasi_customer_penalite');
            $sql = "UPDATE {$tableName} SET amount = 0 WHERE codeSite = {$customerId}";
            $connection->query($sql);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     * @var
     */
    public function resetUnpaidAmount()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("Magento\Customer\Model\Session");

        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();

            $resources = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resources->getConnection();
            $tableName = $resources->getTableName('farmasi_customer_arriere');
            $sql = "UPDATE {$tableName} SET amount = 0 WHERE codeSite = {$customerId}";
            $connection->query($sql);
        }
        return $this;
    }

}