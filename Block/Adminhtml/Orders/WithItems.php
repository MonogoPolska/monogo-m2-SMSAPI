<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Orders;

/**
 * Orders with items report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class WithItems extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Smsapi_Smsapi2';
        $this->_controller = 'adminhtml_orders_withItems';
        $this->_headerText = __('Orders With Items Report');
        parent::_construct();

        $this->buttonList->remove('add');
        $this->addButton(
            'filter_form_submit',
            ['label' => __('Show Report'), 'onclick' => 'filterFormSubmit()', 'class' => 'primary']
        );
    }
}
