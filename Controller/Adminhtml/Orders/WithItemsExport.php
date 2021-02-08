<?php

namespace Smsapi\Smsapi2\Controller\Adminhtml\Orders;

use Smsapi\Smsapi2\Controller\Adminhtml\AbstractReport;

/**
 * Class WithItemsExport
 *
 * @package Smsapi\Smsapi2\Controller\Adminhtml\Orders
 */
class WithItemsExport extends AbstractReport
{
    /**
     * Sales by state report action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Smsapi_Smsapi2::orders_with_items_export_report'
        )->_addBreadcrumb(
            __('Orders With Items Export'),
            __('Orders With Items Export')
        );

        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Orders With Items Export Report'));

        $gridBlock = $this->_view->getLayout()->getBlock('adminhtml_orders_withItemsExport.grid');

        $filterFormBlock = $this->_view->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction([$gridBlock, $filterFormBlock]);

        $this->_view->renderLayout();
    }
}
