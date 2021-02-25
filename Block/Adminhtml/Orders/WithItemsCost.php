<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Orders;

/**
 * Orders with items cost report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class WithItemsCost extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Smsapi_Smsapi2';
        $this->_controller = 'adminhtml_orders_withItemsCost';
        $this->_headerText = __('SMSAPI Report');
        parent::_construct();

        $this->buttonList->remove('add');
        $this->addButton(
            'filter_form_submit',
            ['label' => __('Show Report'), 'onclick' => 'filterFormSubmit()', 'class' => 'primary']
        );
    }
}
