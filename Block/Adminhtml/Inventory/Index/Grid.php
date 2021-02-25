<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Inventory\Index;

/**
 * Inventory report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class Grid extends \Smsapi\Smsapi2\Block\Adminhtml\Grid\InventoryGrid
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('inventoryGrid');
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection \Smsapi\Smsapi2\Model\ResourceModel\Inventory\Collection */
        $collection = $this->_inventoryFactory->create();
        $this->setCollection($collection);
        $collection->prepareForInventoryReport($this->_storeIds);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'sku',
            [
                'header' => __('SKU'),
                'index' => 'sku',
                'sortable' => false,
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name',
            ]
        );

        $this->addColumn(
            'qty',
            [
                'header' => __('Qty'),
                'index' => 'qty',
                'type' => 'number',
                'sortable' => false,
                'header_css_class' => 'col-qty',
                'column_css_class' => 'col-qty',
            ]
        );

        $this->addExportType('*/export/inventoryCsv', __('CSV'));
        $this->addExportType('*/export/inventoryExcel', __('Excel XML'));

        return parent::_prepareColumns();
    }
}
