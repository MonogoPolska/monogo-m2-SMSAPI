<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Inventory;

/**
 * Inventory report
 *
 * @category Smsapi
 * @package  Smsapi\Smsapi2
 */
class Index extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Smsapi_Smsapi2';
        $this->_controller = 'adminhtml_inventory_index';
        $this->_headerText = __('Inventory Report');
        parent::_construct();

        $this->buttonList->remove('add');
    }
}
