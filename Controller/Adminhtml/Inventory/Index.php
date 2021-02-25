<?php

namespace Smsapi\Smsapi2\Controller\Adminhtml\Inventory;

use Smsapi\Smsapi2\Controller\Adminhtml\AbstractReport;

/**
 * Inventory report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class Index extends AbstractReport
{
    /**
     * Inventory report action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Smsapi_Smsapi2::reports_inventory'
        )->_addBreadcrumb(
            __('Inventory Report'),
            __('Inventory Report')
        )->_addContent(
            $this->_view->getLayout()->createBlock(\Smsapi\Smsapi2\Block\Adminhtml\Inventory\Index::class)
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Inventory Report'));
        $this->_view->renderLayout();
    }
}
