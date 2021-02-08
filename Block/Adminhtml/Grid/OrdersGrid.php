<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Grid;

/**
 * Adminhtml orders with items grid block
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class OrdersGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Stores current currency code
     *
     * @var array
     */
    protected $_currentCurrencyCode = null;

    /**
     * Ids of current stores
     *
     * @var array
     */
    protected $_storeIds = [];

    /**
     * @var \Smsapi\Smsapi2\Model\ResourceModel\Sales\Order\CollectionFactory
     */
    protected $_salesOrderFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context                        $context
     * @param \Magento\Backend\Helper\Data                                   $backendHelper
     * @param \Smsapi\Smsapi2\Model\ResourceModel\Sales\Order\CollectionFactory $orderItemCollectionFactory
     * @param array                                                          $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Smsapi\Smsapi2\Model\ResourceModel\Sales\Order\CollectionFactory $orderCollectionFactory,
        array $data = []
    ) {
        $this->_salesOrderFactory = $orderCollectionFactory;
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
