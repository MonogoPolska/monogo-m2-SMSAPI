<?php

namespace Smsapi\Smsapi2\Controller\Adminhtml\Sales;

use Smsapi\Smsapi2\Controller\Adminhtml\AbstractReport;

/**
 * Sales by state report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class ByState extends AbstractReport
{
    /**
     * Sales by state report action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Smsapi_Smsapi2::reports_sales_instate'
        )->_addBreadcrumb(
            __('Sales By State'),
            __('Sales By State')
        );

        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Sales By State Report'));

        $gridBlock = $this->_view->getLayout()->getBlock('adminhtml_sales_byState.grid');

        $filterFormBlock = $this->_view->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction([$gridBlock, $filterFormBlock]);

        $this->_view->renderLayout();
    }
}
