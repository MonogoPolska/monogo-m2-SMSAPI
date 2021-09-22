<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Controller\Adminhtml\Sales;

use Smsapi\Smsapi2\Controller\Adminhtml\AbstractReport;

/**
 * Sales by family report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class Family extends AbstractReport
{
    /**
     * Sales by state report action
     *
     * @return void
     */
    public function execute(): void
    {
        $this->_initAction()->_setActiveMenu(
            'Smsapi_Smsapi2::reports_sales_family'
        )->_addBreadcrumb(
            __('Sales By Family Report'),
            __('Sales By Family Report')
        );

        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Sales By Family Report'));

        $gridBlock = $this->_view->getLayout()->getBlock('adminhtml_sales_family.grid');

        $filterFormBlock = $this->_view->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction([$gridBlock, $filterFormBlock]);

        $this->_view->renderLayout();
    }
}
