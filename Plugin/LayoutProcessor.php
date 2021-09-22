<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class LayoutProcessor
 * @package Smsapi\Smsapi2\Plugin
 */
class LayoutProcessor
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Recipient email config path
     */
    public const SMSAPI_MODULE_ENABLED = 'smsapi/general/enable';

    public const CONFIG_STORE_NAME = 'general/store_information/name';

    public const CONFIG_STORE_STREET_LINE = 'general/store_information/street_line1';

    public const CONFIG_STORE_CITY = 'general/store_information/city';

    public const CONFIG_STORE_POST_CODE = 'general/store_information/postcode';

    /**
     * LayoutProcessor constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Checkout LayoutProcessor after process plugin.
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $processor
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $processor, array $jsLayout): array
    {
        if ($this->getSmsapiModuleEnabled()) {
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
                'description' => __('I consent to receive promotional SMS messages from ') . $this->getStoreName() . __(', with registered office in ') . $this->getStoreAddress(),
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

    /**
     * @return mixed
     */
    public function getSmsapiModuleEnabled()
    {
        $storeScope = ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue(self::SMSAPI_MODULE_ENABLED, $storeScope);
    }

    /**
     * @return mixed
     */
    public function getStoreName()
    {
        $storeScope = ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue(self::CONFIG_STORE_NAME, $storeScope);
    }

    /**
     * @return string
     */
    public function getStoreAddress(): string
    {
        $storeScope = ScopeInterface::SCOPE_STORE;

        $city = $this->scopeConfig->getValue(self::CONFIG_STORE_CITY, $storeScope);
        $address = $this->scopeConfig->getValue(self::CONFIG_STORE_STREET_LINE, $storeScope);
        $postCode = $this->scopeConfig->getValue(self::CONFIG_STORE_POST_CODE, $storeScope);
        return sprintf('%s %s %s', $address, $postCode, $city);
    }
}
