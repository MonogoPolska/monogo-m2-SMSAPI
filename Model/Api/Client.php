<?php

namespace Smsapi\Smsapi2\Model\Api;

use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;
use Smsapi\Client\Service\SmsapiComService;
use Smsapi\Client\Service\SmsapiPlService;
use Smsapi\Client\SmsapiHttpClient;
use Smsapi\Smsapi2\Helper\Config;
use Smsapi\Smsapi2\Helper\Log;

/**
 * API Client
 *
 * @category SMSAPI
 * @package  Smsapi|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Client
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Log
     */
    private $log;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var string[]
     */
    private $services = [
        'com' => 'smsapiComService',
        'pl' => 'smsapiPlService',
    ];

    /**
     * Client constructor.
     *
     * @param Config $config Config
     * @param Log    $log    Log
     */
    public function __construct(
        Config $config,
        Log $log
    ) {
        $this->config = $config;
        $this->log = $log;
    }

    /**
     * Get Service
     *
     * @return SmsapiPlService|SmsapiComService
     */
    public function getService()
    {
        try {
            return (new SmsapiHttpClient())
                ->{$this->services[$this->config->getService()]}($this->config->getApiToken());
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * Ping
     *
     * @return \Smsapi\Client\Feature\Ping\Data\Ping
     */
    public function ping()
    {
        try {
            return $this->getService()->pingFeature()->ping();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * Get current profile
     *
     * @return \Smsapi\Client\Feature\Profile\Data\Profile
     */
    public function getProfile()
    {
        try {
            return $this->getService()->profileFeature()->findProfile();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * Get Senders
     *
     * return array
     */
    public function getSenders()
    {
        try {
            return $this->getService()->smsFeature()->sendernameFeature()->findSendernames();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * Send SMS message
     *
     * @param string $phoneNumber Phone number
     * @param string $message     Message
     *
     * @return \Smsapi\Client\Feature\Sms\Data\Sms
     */
    public function send($phoneNumber, $message)
    {
        try {
            $sms = SendSmsBag::withMessage($phoneNumber, $message);
            $sms->normalize = $this->config->getAllowLong();
            $sms->from = $this->config->getSender();
            $sms->encoding = 'utf-8';
            return $this->getService()->smsFeature()->sendSms($sms);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * Get Errors
     *
     * @return array
     */
    public function getErrors()
    {
        if (!empty($this->errors)) {
            return array_unique($this->errors);
        }

        return [];
    }

    /**
     * Destructor.
     * Log errors
     */
    public function __destruct()
    {
        if (!empty($this->getErrors())) {
            foreach ($this->getErrors() as $error) {
                $this->log->log($error);
            }
        }
    }
}
