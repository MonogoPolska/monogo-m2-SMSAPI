<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\Orders\WithItemsExport;

use Exception;
use Magento\Framework\Exception\FileSystemException;
use Smsapi\Smsapi2\Block\Adminhtml\Grid\OrdersGrid;
use Smsapi\Smsapi2\Block\Sales\OrderitemsExport\Grid\Renderer\Decimal;
use Smsapi\Smsapi2\Model\ResourceModel\Sales\Order\CollectionFactory;

/**
 * Class Grid
 * @package Smsapi\Smsapi2\Block\Adminhtml\Orders\WithItemsExport
 */
class Grid extends OrdersGrid
{

    /**
     * @throws FileSystemException
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setFilterVisibility(false);
        $this->setId('ordersWithItemsExportGrid');
    }

    /**
     * @return $this|Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection CollectionFactory */
        $collection = $this->_salesOrderFactory->create();

        $filter = $this->getParam($this->getVarNameFilter(), []);
        if ($filter) {
            $filter = base64_decode($filter);
            parse_str(urldecode($filter), $data);
        }

        if (!empty($data)) {
            $collection->prepareForWithItemsExportReport($this->_storeIds, $data);
        } else {
            $collection->prepareForWithItemsExportReport($this->_storeIds);
        }

        $this->setCollection($collection);
        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return Grid
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('website', [
            'header' => __('Website'),
            'index' => 'website',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('increment_id', [
            'header' => __('Order Id'),
            'index' => 'increment_id',
            'sortable' => false,
        ]);

//        $this->addColumn('order_chanel', array(
//            'header' => __('Order Chanel'),
//            'index' => 'order_chanel',
//            'type' => 'string',
//            'sortable'  => false
//        ));

        $this->addColumn('checkout_status', [
            'header' => __('Checkout Status'),
            'index' => 'checkout_status',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('status', [
            'header' => __('Order Status'),
            'index' => 'status',
            'type' => 'string',
            'sortable' => false,
        ]);
        $this->addColumn('oms_created_at', [
            'header' => __('Sms alert'),
            'index' => 'oms_created_at',
            'type' => 'boolean',
            'sortable' => false,
        ]);
        $this->addColumn('created_at', [
            'header' => __('Order Date'),
            'index' => 'created_at',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('order_time', [
            'header' => __('Order Time'),
            'index' => 'order_time',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('order_currency_code', [
            'header' => __('Order Curency Code'),
            'index' => 'order_currency_code',
            'type' => 'string',
        ]);

        $this->addColumn('shipping_firstname', [
            'header' => __('Name'),
            'index' => 'shipping.firstname',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('shipping_lastname', [
            'header' => __('Lastname'),
            'index' => 'shipping.lastname',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('shipping_city', [
            'header' => __('City'),
            'index' => 'shipping.city',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('total_items_qty', [
            'header' => __('Total items'),
            'index' => 'total_items_qty',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('subtotal_incl_tax', [
            'header' => __('Subtotal Incl Tax'),
            'index' => 'subtotal_incl_tax',
            'type' => 'price',
            'renderer' => Decimal::class,
            'total' => 'sum',
            'sortable' => false,
        ]);

//        $this->addColumn('customer_id', array(
//            'header' => __('Customer ID'),
//            'index' => 'customer_id',
//            'type' => 'string',
//            'sortable'  => false
//        ));

        $this->addColumn('shipping_postcode', [
            'header' => __('Shipping Postcode'),
            'index' => 'shipping_postcode',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('method', [
            'header' => __('Payment Method'),
            'index' => 'method',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('shipping_country', [
            'header' => __('Shipping Country'),
            'index' => 'shipping_country',
            'type' => 'string',
            'sortable' => false,
        ]);

        $this->addColumn('shipping_amount', [
            'header' => __('Shipping Cost'),
            'index' => 'shipping_amount',
            'sortable' => false,
        ]);

        $this->addExportType('*/export/ordersWithItemsExportCsv', __('CSV'));
        $this->addExportType('*/export/ordersWithItemsExportExcel', __('Excel XML'));

        return parent::_prepareColumns();
    }
}
