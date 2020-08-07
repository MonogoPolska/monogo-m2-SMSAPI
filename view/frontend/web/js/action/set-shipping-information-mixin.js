define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction) {

            var shippingAddress = quote.shippingAddress();
            var billingAddress = quote.billingAddress();



            var alertsms_checkbox = '';
            var sms_alert_input = $("input[name='custom_attributes[sms_alert]']");
            if (sms_alert_input.is(':checked')) {
                alertsms_checkbox = sms_alert_input.val();
            }

            $("input[name='custom_attributes[sms_alert]']").change(function () {
                if ($(this).prop("checked")) {
                    alertsms_checkbox = $(this).val();
                    shippingAddress.customAttributes['send_sms_alert'] = true;
                    billingAddress.customAttributes['send_sms_alert'] = true;
                                            shippingAddress.customAttributes['sms_alert'].label = ' ';
                        billingAddress.customAttributes['sms_alert'].label = ' ';
                }
                alertsms_checkbox = $(this).val();
                shippingAddress.customAttributes['send_sms_alert'] = false;
                billingAddress.customAttributes['send_sms_alert'] = false;
                                        shippingAddress.customAttributes['sms_alert'].label = ' ';
                        billingAddress.customAttributes['sms_alert'].label = ' ';
            });

            if (shippingAddress.customAttributes === undefined) {
                shippingAddress.customAttributes = {};
            }

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            try {

                        shippingAddress.customAttributes['sms_alert'].label = ' ';
                        billingAddress.customAttributes['sms_alert'].label = ' ';

                if (alertsms_checkbox == 'on') {
                    if (shippingAddress.customAttributes[0] !== undefined) {
                        shippingAddress.customAttributes['send_sms_alert'] = true;
                        billingAddress.customAttributes['send_sms_alert'] = true;
                    }

                    if (shippingAddress.customAttributes['sms_alert'] !== undefined)
                    {
                        shippingAddress.customAttributes['sms_alert'].label = ' ';
                        billingAddress.customAttributes['sms_alert'].label = ' ';
                        shippingAddress.customAttributes['send_sms_alert'] = true;
                        billingAddress.customAttributes['send_sms_alert'] = true;
                        shippingAddress.customAttributes[0].value = 1;
                    }
                }

            } catch (e) {
                return originalAction();
            }

            return originalAction();
        });
    };
});