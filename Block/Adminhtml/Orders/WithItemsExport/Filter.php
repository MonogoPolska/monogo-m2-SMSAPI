<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\Orders\WithItemsExport;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Data\Form\Element\Fieldset;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Sales\Block\Adminhtml\Report\Filter\Form;
use Magento\Sales\Model\Order\ConfigFactory;

class Filter extends Form
{

    /**
     * @var Session
     */
    protected $authSession;

    /**
     * Filter constructor.
     * @param Session $authSession
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param ConfigFactory $orderConfig
     * @param array $data
     */
    public function __construct(
        Session $authSession,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        ConfigFactory $orderConfig,
        array $data = []
    ) {
        $this->_storeManager = $context->getStoreManager();
        $this->authSession = $authSession;
        parent::__construct($context, $registry, $formFactory, $orderConfig, $data);
    }

    /**
     * @return $this|Filter
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

    /**
     * @param false $addAll
     * @return array
     */
    public function getWebsiteNames($addAll = false): array
    {
        $websiteList = [];
        $websites = $this->_storeManager->getWebsites();
        foreach ($websites as $website) {
            $websiteList[$website->getId()] = $website->getName();
        }
        return $websiteList;
    }
}
