<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Orders;

/**
 * Orders + items report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class AndItems extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Smsapi_Smsapi2';
        $this->_controller = 'adminhtml_orders_andItems';
        $this->_headerText = __('Orders And Items Report');
        parent::_construct();

        $this->buttonList->remove('add');
        $this->addButton(
            'filter_form_submit',
            ['label' => __('Show Report'), 'onclick' => 'filterFormSubmit()', 'class' => 'primary']
        );
    }
}
