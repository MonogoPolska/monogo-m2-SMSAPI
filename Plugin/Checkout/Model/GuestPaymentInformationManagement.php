<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Plugin\Checkout\Model;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class GuestPaymentInformationManagement
 * @package Smsapi\Smsapi2\Plugin\Checkout\Model
 */
class GuestPaymentInformationManagement
{
    /**
     * @param \Magento\Checkout\Model\GuestPaymentInformationManagement $subject
     * @param $cartId
     * @param $email
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     */
    public function beforeSavePaymentInformation(
        \Magento\Checkout\Model\GuestPaymentInformationManagement $subject,
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        if (!$billingAddress) {
            return;
        }

        if ($billingAddress->getExtensionAttributes()) {
            $billingAddress->setSmsAlert((int)$billingAddress->getExtensionAttributes()->getSmsAlert());
        } else {
            $billingAddress->setSmsAlert(0);
        }
    }
}
