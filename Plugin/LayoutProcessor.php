<?php

namespace Smsapi\Smsapi2\Plugin;

class LayoutProcessor
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Recipient email config path
     */
    const SMSAPI_MODULE_ENABLED = 'smsapi/general/enable';

    const CONFIG_STORE_NAME = 'general/store_information/name';
    const CONFIG_STORE_STREET_LINE = 'general/store_information/street_line1';
    const CONFIG_STORE_CITY = 'general/store_information/city';
    const CONFIG_STORE_POST_CODE = 'general/store_information/postcode';

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Checkout LayoutProcessor after process plugin.
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $processor
     * @param array                                            $jsLayout
     *
     * @return array
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $processor, $jsLayout)
    {
        if($this->getSmsapiModuleEnabled()) {

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['before-form']['children']['sms_alert'] = [
                'component' => 'Smsapi_Smsapi2/js/view/form/element/sms_alert',
                'config' => [
                    'customScope' => 'shippingAddress.extension_attributes',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/checkbox',
                    'custom_entry' => null,
                ],
                'dataScope' => 'shippingAddress.custom_attributes.sms_alert',
                'label' => __('SMS Order Notifications'),
                'description' => __('Send SMS order notifications to the phone number.'),
                'provider' => 'checkoutProvider',
                'visible' => true,
                'checked' => true,
                'validation' => [],
                'sortOrder' => 925,
                'custom_entry' => null,
            ];

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['before-form']['children']['sms_marketing'] = [
                'component' => 'Smsapi_Smsapi2/js/view/form/element/sms_marketing',
                'config' => [
                    'customScope' => 'shippingAddress.extension_attributes',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/checkbox',
                    'custom_entry' => null,
                ],
                'dataScope' => 'shippingAddress.custom_attributes.sms_marketing',
                'label' => null,
                'description' => __('I consent to receive promotional SMS messages from '). $this->getStoreName().__(', with registered office in ').$this->getStoreAddress(),
                'provider' => 'checkoutProvider',
                'visible' => true,
                'checked' => true,
                'validation' => [],
                'sortOrder' => 926,
                'custom_entry' => null,
            ];
        }

        return $jsLayout;
    }


    public function getSmsapiModuleEnabled() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue(self::SMSAPI_MODULE_ENABLED, $storeScope);
    }

    public function getStoreName() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue(self::CONFIG_STORE_NAME, $storeScope);
    }

    public function getStoreAddress() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        $city = $this->scopeConfig->getValue(self::CONFIG_STORE_CITY, $storeScope);
        $address = $this->scopeConfig->getValue(self::CONFIG_STORE_STREET_LINE, $storeScope);
        $postCode = $this->scopeConfig->getValue(self::CONFIG_STORE_POST_CODE, $storeScope);
        return sprintf('%s %s %s', $address, $postCode, $city);
    }
}
