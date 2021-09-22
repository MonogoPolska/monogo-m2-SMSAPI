<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\Inventory\Index;

use Exception;
use Magento\Framework\Exception\FileSystemException;
use Smsapi\Smsapi2\Block\Adminhtml\Grid\InventoryGrid;
use Smsapi\Smsapi2\Model\ResourceModel\Inventory\Collection;

/**
 * Inventory report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class Grid extends InventoryGrid
{
    /**
     * @throws FileSystemException
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('inventoryGrid');
    }

    /**
     * @return $this|Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection Collection */
        $collection = $this->_inventoryFactory->create();
        $this->setCollection($collection);
        $collection->prepareForInventoryReport($this->_storeIds);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * @return Grid
     * @throws Exception
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
