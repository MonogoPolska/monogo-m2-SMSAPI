<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\Grid;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Smsapi\Smsapi2\Model\ResourceModel\Inventory\CollectionFactory;

/**
 * Adminhtml inventory report grid block
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class InventoryGrid extends Extended
{
    /**
     * Ids of current stores
     *
     * @var array
     */
    protected $_storeIds = [];

    /**
     * @var CollectionFactory
     */
    protected $_inventoryFactory;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param CollectionFactory $inventoryFactory ,
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $inventoryFactory,
        array $data = []
    ) {
        $this->_inventoryFactory = $inventoryFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * StoreIds setter
     * @codeCoverageIgnore
     *
     * @param array $storeIds
     * @return $this
     */
    public function setStoreIds(array $storeIds)
    {
        $this->_storeIds = $storeIds;
        return $this;
    }
}
