<?php

namespace Smsapi\Smsapi2\Controller\Adminhtml\Export;

use Smsapi\Smsapi2\Block\Adminhtml\Orders\WithItemsExport\Grid;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Reports\Controller\Adminhtml\Report\Sales;

/**
 * Class OrdersWithItemsExportExcel
 *
 * @package Smsapi\Smsapi2\Controller\Adminhtml\Export
 */
class OrdersWithItemsExportExcel extends Sales
{
    /**
     * Export orders with items grid to Excel format
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        $fileName = 'orders_with_items_export.xml';
        $grid = $this->_view->getLayout()->createBlock(Grid::class);
        $this->_initReportAction($grid);
        return $this->_fileFactory->create($fileName, $grid->getExcel(), DirectoryList::VAR_DIR);
    }
}
