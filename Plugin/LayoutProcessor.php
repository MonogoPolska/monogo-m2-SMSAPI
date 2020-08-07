<?php

namespace Smsapi\Smsapi2\Plugin;

class LayoutProcessor
{
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

        return $jsLayout;
    }
}
