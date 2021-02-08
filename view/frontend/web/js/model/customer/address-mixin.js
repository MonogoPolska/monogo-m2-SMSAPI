define([
    'jquery',
    'mage/utils/wrapper',
    'mage/translate'
], function ($, wrapper) {
    'use strict';

    return function (addressModel) {
        return wrapper.wrap(addressModel, function (originalAction) {
            var address = originalAction();

            if (address.customAttributes.send_sms_alert == true) {
                address.customAttributes['send_sms_alert'] = true;
            }

            if (address['customAttributes']['sms_alert'] !== undefined) {
                address['customAttributes']['sms_alert'].label = ' ';
            }

            if (address.customAttributes.send_sms_alert == true) {
                address.customAttributes['send_sms_alert'] = true;
            }

            if (address['customAttributes']['sms_alert'] !== undefined) {
                address['customAttributes']['sms_alert'].label = ' ';
            }

            return address;
        });
    };
});
