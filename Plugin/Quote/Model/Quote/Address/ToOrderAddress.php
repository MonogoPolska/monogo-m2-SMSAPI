<?php

namespace Smsapi\Smsapi2\Plugin\Quote\Model\Quote\Address;

class ToOrderAddress
{
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Address\ToOrderAddress $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Address $address,
        $data = []
    ) {
        $result = $proceed($address, $data);

        $json = json_decode(file_get_contents('php://input'), true);

        if (isset($json['billingAddress']['customAttributes']['send_sms_alert'])) {
            //for registered customer with saved address
            if ($json['billingAddress']['customAttributes']['send_sms_alert']) {
                $result->setSmsAlert($json['billingAddress']['customAttributes']['send_sms_alert']);
            }
        } elseif (isset($json['billingAddress']['customAttributes'][0]['value'])) {
            //for guest
            if ($json['billingAddress']['customAttributes'][0]['value']) {
                $result->setSmsAlert(true);
            }
        } else {
            if (isset($json['billingAddress']['customAttributes']['sms_alert']['value'])) {
                $result->setSmsAlert($json['billingAddress']['customAttributes']['sms_alert']['value']);
            } else {
                $result->setSmsAlert(null);
            }
        }

        return $result;
    }
}
