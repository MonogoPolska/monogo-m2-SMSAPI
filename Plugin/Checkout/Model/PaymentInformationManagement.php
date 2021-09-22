<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Plugin\Checkout\Model;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class PaymentInformationManagement
 * @package Smsapi\Smsapi2\Plugin\Checkout\Model
 */
class PaymentInformationManagement
{
    public function beforeSavePaymentInformation(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
        $cartId,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ): void {
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
