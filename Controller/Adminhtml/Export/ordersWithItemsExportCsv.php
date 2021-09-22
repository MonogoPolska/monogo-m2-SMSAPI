<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Controller\Adminhtml\Export;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
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
     * @return ResponseInterface|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        $fileName = 'orders_with_items_export.csv';
        $grid = $this->_view->getLayout()->createBlock(Grid::class);
        $this->_initReportAction($grid);
        return $this->_fileFactory->create($fileName, $grid->getCsvFile(), DirectoryList::VAR_DIR);
    }
}
