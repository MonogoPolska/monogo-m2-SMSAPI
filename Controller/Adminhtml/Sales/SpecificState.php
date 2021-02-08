<?php

namespace Smsapi\Smsapi2\Controller\Adminhtml\Sales;

use Smsapi\Smsapi2\Controller\Adminhtml\AbstractReport;

/**
 * Sales in specific state report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class SpecificState extends AbstractReport
{
    /**
     * Sales in state report action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Smsapi_Smsapi2::sales_by_specific_state'
        )->_addBreadcrumb(
            __('Sales In specific state by product type'),
            __('Sales In specific state by product type')
        );

        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Sales In specific state by product type'));

        $gridBlock = $this->_view->getLayout()->getBlock('adminhtml_sales_specificState.grid');

        $filterFormBlock = $this->_view->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction([$gridBlock, $filterFormBlock]);

        $this->_view->renderLayout();
    }
}
