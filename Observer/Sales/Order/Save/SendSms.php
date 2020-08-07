<?php

namespace Smsapi\Smsapi2\Observer\Sales\Order\Save;

use Magento\Framework\Event\ObserverInterface;
use Smsapi\Smsapi2\Helper\Config;
use Smsapi\Smsapi2\Helper\Log;
use Smsapi\Smsapi2\Helper\Message;
use Smsapi\Smsapi2\Model\Api\Client;

/**
 * Class SendSms
 *
 * @category SMSAPI
 * @package  Smsapi|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class SendSms implements ObserverInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Log
     */
    protected $log;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var boolean
     */
    private $sendSmsFlag = false;

    /**
     * SendSms constructor.
     *
     * @param Config  $config  Config
     * @param Log     $log     Log
     * @param Message $message Message
     * @param Client  $client  Client
     */
    public function __construct(
        Config $config,
        Log $log,
        Message $message,
        Client $client
    ) {
        $this->config = $config;
        $this->log = $log;
        $this->message = $message;
        $this->client = $client;
    }

    /**
     * Send SMS on configured status
     *
     * @param \Magento\Framework\Event\Observer $observer Observer
     *
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->order = $observer->getEvent()->getOrder();
        if ($this->order instanceof \Magento\Framework\Model\AbstractModel) {
            /** @var $order \Magento\Sales\Model\Order */
            $this->send();
        }
    }

    /**
     * Send SMS message message
     *
     * @return void
     */
    protected function send()
    {
        if ($this->getCurrentStatus() != $this->getPreviousStatus()) {
            $templates = $this->config->getTemplates();
            if (is_array($templates)) {
                $template = null;
                if ($this->getPreviousStatus() !== null && key_exists($this->getPreviousStatus() . '_' . $this->getCurrentStatus(), $templates)) {
                    $template = $templates[$this->getPreviousStatus() . '_' . $this->getCurrentStatus()];
                    $this->setSendSmsFlag(true);
                } elseif ($this->getPreviousStatus() === null && key_exists('new_' . $this->getCurrentStatus(), $templates)) {
                    $template = $templates['new_' . $this->getCurrentStatus()];
                    $this->setSendSmsFlag(true);
                } elseif (key_exists($this->getCurrentStatus(), $templates)) {
                    $template = $templates[$this->getCurrentStatus()];
                    $this->setSendSmsFlag(true);
                }
                if (empty($this->order->getShippingAddress()->getTelephone()) || empty($this->order->getBillingAddress()->getTelephone())) {
                    $this->log->log('Order ' . $this->order->getId() . ': Empty phone number for customer ');
                }
                try {
                    $smsMessage = $this->message->parseMessage($template['tmeplate'], $this->order);
                    $phoneNumber = $this->getPhoneNumber();
                    if ($this->getSendSmsFlag()) {
                        if ($phoneNumber && ($template['send_to_client'] && $this->order->getShippingAddress()->getSmsAlert())) {
                            $result = $this->client->send($this->order->getShippingAddress()->getTelephone(), $smsMessage);
                            $this->order->addStatusToHistory($this->getCurrentStatus(), __('Sended SMS content:') . ' ' . $smsMessage, true);
                            $this->smsLog($result);
                        }
                    }
                } catch (\Exception $e) {
                }
                if ($template['send_cc']) {
                    try {
                        foreach ($this->config->getCcNumbers() as $ccNumber) {
                            $smsMessageCC = $this->message->parseMessage($template['tmeplate_cc'], $this->order);
                            if ($ccNumber) {
                                $result = $this->client->send($ccNumber, $smsMessageCC);
                                $this->notificationLog($result);
                            }
                        }
                    } catch (\Exception $e) {
                    }
                }
            }
        }
    }

    /**
     * Get Current Order Status
     *
     * @return string
     */
    protected function getCurrentStatus()
    {
        return $this->order->getStatus();
    }

    /**
     * Get Previous Order Status
     *
     * @return string
     */
    protected function getPreviousStatus()
    {
        $origData = $this->order->getOrigData();
        if (!empty($origData) && key_exists('status', $origData)) {
            return $origData['status'];
        } else {
            return null;
        }
    }

    /**
     * Get phone number from order
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        if (!empty($this->order->getShippingAddress()->getTelephone())) {
            return $this->order->getShippingAddress()->getTelephone();
        }
        if (!empty($this->order->getBillingAddress()->getTelephone())) {
            return $this->order->getBillingAddress()->getTelephone();
        }

        if (!empty($this->order->getCustomer()->getTelephone())) {
            return $this->order->getCustomer()->getTelephone();
        }
        return null;
    }

    /**
     * Log sent sms
     *
     * @return void
     */
    private function smsLog($result)
    {
        $this->log->log(
            'Message ' . $result->id . ' has been sent to '
            . $result->number
            . ' at ' . date('Y-m-d H:i:s', $result->dateSent->getTimestamp())
            . ' length: ' . $result->content->length . ' (' . $result->content->parts . ' parts)'
            . ' with status: ' . $result->status
        );
    }

    /**
     * Log sent sms notification
     *
     * @return void
     */
    private function notificationLog($result)
    {
        $this->log->log(
            'Notification message ' . $result->id . ' has been sent to '
            . $result->number
            . ' at ' . date('Y-m-d H:i:s', $result->dateSent->getTimestamp())
            . ' length: ' . $result->content->length . ' (' . $result->content->parts . ' parts)'
            . ' with status: ' . $result->status
        );
    }

    /**
     * @return boolean
     */
    public function getSendSmsFlag()
    {
        return $this->sendSmsFlag;
    }

    /**
     * @param boolean $sendSmsFlag
     */
    public function setSendSmsFlag($sendSmsFlag)
    {
        $this->sendSmsFlag = $sendSmsFlag;
    }
}
