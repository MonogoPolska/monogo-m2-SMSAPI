<?php

namespace Smsapi\Smsapi2\Controller\Adminhtml\Export;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Reports\Controller\Adminhtml\Report\Sales;
use Smsapi\Smsapi2\Block\Adminhtml\Orders\WithItemsExport\Grid;

/**
 * Class ordersWithItemsExportCsv
 *
 * @package Smsapi\Smsapi2\Controller\Adminhtml\Export
 */
class ordersWithItemsExportCsv extends Sales
{
    /**
     * Export coupons usage grid to CSV format
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        $fileName = 'orders_with_items_export.csv';
        $grid = $this->_view->getLayout()->createBlock(Grid::class);
        $this->_initReportAction($grid);
        return $this->_fileFactory->create($fileName, $grid->getCsvFile(), DirectoryList::VAR_DIR);
    }
}
