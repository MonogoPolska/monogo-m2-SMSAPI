<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Plugin\Quote\Model\Quote\Address;

use Closure;
use Magento\Quote\Model\Quote\Address;

/**
 * Class ToOrderAddress
 * @package Smsapi\Smsapi2\Plugin\Quote\Model\Quote\Address
 */
class ToOrderAddress
{
    /**
     * @param Address\ToOrderAddress $subject
     * @param Closure $proceed
     * @param Address $address
     * @param array $data
     * @return mixed
     */
    public function aroundConvert(
        Address\ToOrderAddress $subject,
        Closure $proceed,
        Address $address,
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
