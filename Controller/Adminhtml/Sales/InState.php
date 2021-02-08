<?php

namespace Smsapi\Smsapi2\Controller\Adminhtml\Sales;

use Smsapi\Smsapi2\Controller\Adminhtml\AbstractReport;

/**
 * Sales in state report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class InState extends AbstractReport
{
    /**
     * Sales in state report action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Smsapi_Smsapi2::reports_sales_instate'
        )->_addBreadcrumb(
            __('Sales In States'),
            __('Sales In States')
        );

        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Sales In States Report'));

        $gridBlock = $this->_view->getLayout()->getBlock('adminhtml_sales_inState.grid');

        $filterFormBlock = $this->_view->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction([$gridBlock, $filterFormBlock]);

        $this->_view->renderLayout();
    }
}
