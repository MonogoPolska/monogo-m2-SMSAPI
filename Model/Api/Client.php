<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Model\Api;

use Exception;
use Magento\Framework\HTTP\Adapter\Curl;
use Smsapi\Client\Curl\SmsapiHttpClient;
use Smsapi\Client\Feature\Ping\Data\Ping;
use Smsapi\Client\Feature\Profile\Data\Profile;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;
use Smsapi\Client\Feature\Sms\Data\Sms;
use Smsapi\Client\Service\SmsapiComService;
use Smsapi\Client\Service\SmsapiPlService;
use Smsapi\Smsapi2\Helper\Config;
use Smsapi\Smsapi2\Helper\Log;
use Smsapi\Smsapi2\Helper\OauthHelper;

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
     * @var OauthHelper
     */
    private $oauthHelper;

    /**
     * @var string[]
     */
    private $services = [
        'com' => 'smsapiComService',
        'pl' => 'smsapiPlService',
    ];

    /**
     * @var Curl
     */
    private $curl;

    /**
     * Client constructor.
     * @param Config $config
     * @param Log $log
     * @param OauthHelper $oauthHelper
     * @param Curl $curl
     */
    public function __construct(
        Config $config,
        Log $log,
        OauthHelper $oauthHelper,
        Curl $curl
    ) {
        $this->config = $config;
        $this->log = $log;
        $this->oauthHelper = $oauthHelper;
        $this->curl = $curl;
    }

    /**
     * Get Service
     *
     * @return SmsapiPlService|SmsapiComService|null
     */
    public function getService()
    {
        try {
            if ($this->config->getOauthEnable()) {
                return (new SmsapiHttpClient())
                    ->smsapiPlService($this->config->getOauthBearer());
            }
            if ($this->config->getTokenEnable()) {
                return (new SmsapiHttpClient())
                    ->{$this->services[$this->config->getService()]}($this->config->getApiToken());
            }
        } catch (Exception $e) {
            $this->errors[] = 'getService' . $e->getMessage();
        }
        return null;
    }

    /**
     * Ping
     *
     * @return Ping|null
     */
    /**
     * @return Ping|null
     */
    /**
     * @return Ping
     */
    public function ping(): ?Ping
    {
        try {
            if ($this->getService()) {
                return $this->getService()->pingFeature()->ping();
            }
        } catch (Exception $e) {
            $this->errors[] = 'ping' . $e->getMessage();
        }
        return null;
    }

    /**
     * Get current profile
     * @return Profile|null
     */
    public function getProfile(): ?Profile
    {
        try {
            if ($this->getService()) {
                return $this->getService()->profileFeature()->findProfile();
            }
        } catch (Exception $e) {
            $this->errors[] = 'getProfile ' . $e->getMessage();
        }
        return null;
    }

    /**
     * Get Senders
     * @return array|null
     */
    public function getSenders(): ?array
    {
        try {
            if ($this->getService()) {
                return $this->getService()->smsFeature()->sendernameFeature()->findSendernames();
            }
            return [];
        } catch (Exception $e) {
            $this->errors[] = 'getSenders ' . $e->getMessage();
        }
        return null;
    }

    /**
     * Send SMS message
     * @param string $phoneNumber
     * @param string $message
     * @return Sms|null
     */
    public function send(string $phoneNumber, string $message): ?Sms
    {
        $this->log->log('sms send init for ' . $phoneNumber . ' with message ' . $message);

        try {
            $sms = SendSmsBag::withMessage($phoneNumber, $message);
            $sms->partnerId = 'MAGENTO';
            $sms->normalize = $this->config->getAllowLong();
            $sms->from = $this->config->getSender();
            $sms->encoding = 'utf-8';
            return $this->getService()->smsFeature()->sendSms($sms);
        } catch (Exception $e) {
            $this->errors[] = 'sendSms ' . $e->getMessage();
        }
        return null;
    }

    /**
     * Get Errors
     *
     * @return array
     */
    public function getErrors(): array
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
