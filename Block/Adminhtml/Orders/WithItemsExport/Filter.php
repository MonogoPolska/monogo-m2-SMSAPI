<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Orders\WithItemsExport;

use Magento\Framework\Data\Form\Element\Fieldset;
use Magento\Sales\Block\Adminhtml\Report\Filter\Form;

/**
 * Class Filter
 *
 * @package Smsapi\Smsapi2\Block\Adminhtml\Orders\WithItemsExport
 */
class Filter extends Form
{
    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    public function __construct(
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Sales\Model\Order\ConfigFactory $orderConfig,
        array $data = []
    ) {
        $this->_storeManager = $context->getStoreManager();
        $this->authSession = $authSession;
        parent::__construct($context, $registry, $formFactory, $orderConfig, $data);
    }

    /**
     * Preparing form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        /** @var Fieldset $fieldset */
        $fieldset = $this->getForm()->getElement('base_fieldset');

        if (is_object($fieldset) && $fieldset instanceof Fieldset) {
            $fieldset->removeField('period_type');
            $fieldset->removeField('report_type');
            $fieldset->removeField('show_empty_rows');
            $fieldset->addField(
                'website_id',
                'select',
                [
                    'required' => true,
                    'name' => 'website_id',
                    'options' => $this->getWebsiteNames(),
                    'label' => __('Website'),
                    'title' => __('Website'),
                ],
                'to'
            );
        }

        return $this;
    }

    public function getWebsiteNames($addAll = false)
    {
        $websiteList = [];
        $websites = $this->_storeManager->getWebsites();
        foreach ($websites as $website) {
            $websiteList[$website->getId()] = $website->getName();
        }
        return $websiteList;
    }
}
