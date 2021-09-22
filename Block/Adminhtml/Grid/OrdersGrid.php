<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\Grid;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Smsapi\Smsapi2\Model\ResourceModel\Sales\Order\CollectionFactory;

/**
 * Adminhtml orders with items grid block
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class OrdersGrid extends Extended
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
     * @var CollectionFactory
     */
    protected $_salesOrderFactory;

    /**
     * OrdersGrid constructor.
     * @param Context $context
     * @param Data $backendHelper
     * @param CollectionFactory $orderCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $orderCollectionFactory,
        array $data = []
    ) {
        $this->_salesOrderFactory = $orderCollectionFactory;
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
