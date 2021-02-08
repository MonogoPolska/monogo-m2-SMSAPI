<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Orders;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class WithItemsExport
 *
 * @package Smsapi\Smsapi2\Block\Adminhtml\Orders
 */
class WithItemsExport extends Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Smsapi_Smsapi2';
        $this->_controller = 'adminhtml_orders_withItemsExport';
        $this->_headerText = __('Order + Items Export Report');
        parent::_construct();

        $this->buttonList->remove('add');
        $this->addButton(
            'filter_form_submit',
            ['label' => __('Show Report'), 'onclick' => 'filterFormSubmit()', 'class' => 'primary']
        );
    }
}
