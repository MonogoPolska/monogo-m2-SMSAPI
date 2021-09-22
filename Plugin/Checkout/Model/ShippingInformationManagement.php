<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Plugin\Checkout\Model;

use Magento\Checkout\Api\Data\ShippingInformationInterface;

/**
 * Class ShippingInformationManagement
 * @package Smsapi\Smsapi2\Plugin\Checkout\Model
 */
class ShippingInformationManagement
{

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ): void {
        $shippingAddress = $addressInformation->getShippingAddress();
        $billingAddress = $addressInformation->getBillingAddress();

        if ($shippingAddress->getExtensionAttributes()) {
            $shippingAddress->setSmsAlert((int)$shippingAddress->getExtensionAttributes()->getSmsAlert());
        } else {
            $shippingAddress->setSmsAlert(0);
        }

        if ($billingAddress->getExtensionAttributes()) {
            $billingAddress->setSmsAlert((int)$billingAddress->getExtensionAttributes()->getSmsAlert());
        } else {
            $billingAddress->setSmsAlert(0);
        }
    }
}
