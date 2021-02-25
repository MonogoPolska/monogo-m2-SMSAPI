define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction) {

            var billingAddress = quote.billingAddress();
            var shippingAddress = quote.shippingAddress();


            if (billingAddress.customAttributes === undefined) {
                billingAddress.customAttributes = {};
            }

            if (billingAddress['extension_attributes'] === undefined) {
                billingAddress['extension_attributes'] = {};
            }

            var shippingCustomAtrributesCopy = shippingAddress.customAttributes;
            billingAddress.customAttributes = shippingCustomAtrributesCopy;

            try {
                if (shippingAddress.customAttributes.send_sms_alert === true) {
                    billingAddress.customAttributes['send_sms_alert'] = shippingAddress.customAttributes[0];
                    billingAddress['customAttributes']['sms_alert'].value = true;
                    billingAddress['customAttributes']['sms_alert'].label = ' ';
                    billingAddress['extension_attributes']['sms_alert'] = true;
                } else {
                    billingAddress['extension_attributes']['sms_alert'].label = ' ';
                    billingAddress['customAttributes']['sms_alert'].label = ' ';
                }
            } catch (e) {
                return originalAction();
            }

            return originalAction();
        });
    };
});