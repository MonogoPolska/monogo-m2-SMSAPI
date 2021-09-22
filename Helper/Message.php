<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\Helper\Data as Currency;
use Magento\Sales\Model\Order;
use Smsapi\Smsapi2\Block\Adminhtml\Form\Field\Status;

/**
 * Class Message
 * @package Smsapi\Smsapi2\Helper
 */
class Message extends AbstractHelper
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var Status
     */
    protected $status;

    /**
     * Message constructor.
     *
     * @param Context $context Context
     * @param Config $config Config
     * @param Currency $currency Currency
     * @param Status $status Status
     */
    public function __construct(
        Context $context,
        Config $config,
        Currency $currency,
        Status $status
    ) {
        $this->config = $config;
        $this->currency = $currency;
        $this->status = $status;
        parent::__construct($context);
    }

    /**
     * Parse variables
     * @param string $message
     * @param Order $object
     * @return string
     */
    public function parseMessage(string $message, Order $object): string
    {
        $message = str_replace(
            '{NAME}',
            $object->getCustomerFirstname() ? $object->getCustomerFirstname() : $object->getBillingAddress()->getFirstname(),
            $message
        );
        $message = str_replace(
            '{LASTNAME}',
            $object->getCustomerLastname() ? $object->getCustomerLastname() : $object->getBillingAddress()->getLastname(),
            $message
        );
        $message = str_replace('{EMAIL}', $object->getCustomerEmail(), $message);
        $message = str_replace(
            '{ORDERVALUEGROSS}',
            $this->currency->currency($object->getGrandTotal(), true, false),
            $message
        );
        $message = str_replace(
            '{ORDERVALUENET}',
            $this->currency->currency($object->getGrandTotal() - $object->getTaxAmount(), true, false),
            $message
        );
        $message = str_replace('{ORDERSTATUS}', $this->getStatusLabel($object->getStatus()), $message);
        $message = str_replace('{ORDERNUMBER}', '#' . $object->getIncrementId(), $message);
        $message = str_replace(
            '{SHIPPINGADDRESS}',
            implode(' ', $object->getShippingAddress()->getStreet())
            . ' ' . $object->getShippingAddress()->getPostcode()
            . ' ' . $object->getShippingAddress()->getCity(),
            $message
        );
        $message = str_replace('{TRACKINGNUMBER}', $this->getTrackingNumbersForSMS($object), $message);
        $message = str_replace('{STORENAME}', $this->config->getStoreName(), $message);
        return $message;
    }

    /**
     * Get Status label by Code
     * @param string $statusCode
     * @return string|null
     */
    protected function getStatusLabel(string $statusCode): ?string
    {
        $statusArray = $this->status->getStatusOptions();
        if (key_exists($statusCode, $statusArray)) {
            return $statusArray[$statusCode];
        }
    }

    /**
     * @param Order $object
     * @return string
     */
    private function getTrackingNumbersForSMS(Order $object): string
    {
        $trackNumbers = [];
        $tracksCollection = $object->getTracksCollection();

        foreach ($tracksCollection->getItems() as $track) {
            $trackNumbers[] = $track->getTrackNumber();
        }

        return implode(' ', $trackNumbers);
    }
}
