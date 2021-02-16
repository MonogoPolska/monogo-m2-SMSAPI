<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Grid;

/**
 * Adminhtml inventory report grid block
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class InventoryGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Ids of current stores
     *
     * @var array
     */
    protected $_storeIds = [];

    /**
     * @var \Smsapi\Smsapi2\Model\ResourceModel\Inventory\CollectionFactory
     */
    protected $_inventoryFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context                         $context
     * @param \Magento\Backend\Helper\Data                                    $backendHelper
     * @param \Smsapi\Smsapi2\Model\ResourceModel\Inventory\CollectionFactory $inventoryFactory,
     * @param array                                                           $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Smsapi\Smsapi2\Model\ResourceModel\Inventory\CollectionFactory $inventoryFactory,
        array $data = []
    ) {
        $this->_inventoryFactory = $inventoryFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * StoreIds setter
     * @codeCoverageIgnore
     *
     * @param  array $storeIds
     * @return $this
     */
    public function setStoreIds($storeIds)
    {
        $this->_storeIds = $storeIds;
        return $this;
    }
}
