<?php

namespace Smsapi\Smsapi2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package Smsapi\Smsapi2\Helper
 */
class Config extends AbstractHelper
{
    const CONFIG_PATH = 'smsapi/';

    const ENABLED = 'general/enable';

    const API_TOKEN = 'general/apitoken';

    const ALLOW_LONG = 'general/allow_long';

    const STORE_NAME = 'general/storename';

    const SENDER = 'general/sender';

    const ALERT_LIMIT = 'general/alert_limit';

    const SERVICE = 'general/service';

    const CC_NUMBERS = 'general/ccnumbers';

    const TEMPLATES = 'templates/messages';

    const ENABLE_LOG = 'debug/enable_log';

    const BEARER = 'oauth/bearer';

    const CLIENT_ID = 'oauth/client_id';

    const CLIENT_SECRET = 'oauth/client_secret';

    const REFRESH_TOKEN = 'oauth/refresh_token';

    const OAUTH_ENABLE = 'general/oauth_enable';

    const TOKEN_ENABLE = 'general/apitoken_enable';

    protected $templates;

    /**
     * Get Store Config by key
     *
     * @param string $config_path Path
     *
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Store Config by key
     *
     * @param string $config_path Path
     *
     * @return mixed
     */
    public function setConfig($config_path, $value)
    {
        return $this->scopeConfig->setValue(
            $config_path,
            $value,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Is Module enabled
     *
     * @return int
     */
    public function getIsEnabled()
    {
        return $this->getConfig(self::CONFIG_PATH . self::ENABLED);
    }

    /**
     * Get API User
     *
     * @return string
     */
    public function getApiToken()
    {
        return $this->getConfig(self::CONFIG_PATH . self::API_TOKEN);
    }

    /**
     * Check if credentials are not empty
     *
     * @return bool
     */
    public function validateCredentials()
    {
        return (bool)!empty($this->getApiToken());
    }

    /**
     * Get Allow long messages
     *
     * @return int
     */
    public function getAllowLong()
    {
        return $this->getConfig(self::CONFIG_PATH . self::ALLOW_LONG);
    }

    /**
     * Get Store Name in message
     *
     * @return string
     */
    public function getStoreName()
    {
        return $this->getConfig(self::CONFIG_PATH . self::STORE_NAME);
    }

    /**
     * Get Sender
     *
     * @return string
     */
    public function getSender()
    {
        return $this->getConfig(self::CONFIG_PATH . self::SENDER);
    }

    /**
     * Get Alert limit
     *
     * @return int
     */
    public function getAlertLimit()
    {
        return $this->getConfig(self::CONFIG_PATH . self::ALERT_LIMIT);
    }

    /**
     * Get Service
     *
     * @return string
     */
    public function getService()
    {
        $halko = $this->getConfig(self::CONFIG_PATH . self::SERVICE);
        return $halko;
    }

    /**
     * Get Service
     *
     * @return array
     */
    public function getCcNumbers()
    {
        return array_unique(explode(',', $this->getConfig(self::CONFIG_PATH . self::CC_NUMBERS)));
    }

    /**
     * Get SMS Templates
     *
     * @return array
     */
    public function getTemplates()
    {
        if (empty($this->templates) && $this->getIsEnabled()) {
            $templatesConfig = json_decode($this->getConfig(self::CONFIG_PATH . self::TEMPLATES), true);
            if ($templatesConfig) {
                foreach ($templatesConfig as $templateConfig) {
                    if ($templateConfig['col_2'] == 1) {
                        if ($templateConfig['col_6'] != 'any') {
                            $this->templates[$templateConfig['col_6'] . '_' . $templateConfig['col_1']] = [
                                'tmeplate' => $templateConfig['col_3'],
                                'send_cc' => $templateConfig['col_4'],
                                'tmeplate_cc' => $templateConfig['col_5'],
                                'status_from' => $templateConfig['col_6'],
                                'send_to_client' => $templateConfig['col_7'],
                            ];
                        }
                        if ($templateConfig['col_6'] == 'any') {
                            $this->templates[$templateConfig['col_1']] = [
                                'tmeplate' => $templateConfig['col_3'],
                                'send_cc' => $templateConfig['col_4'],
                                'tmeplate_cc' => $templateConfig['col_5'],
                                'status_from' => $templateConfig['col_6'],
                                'send_to_client' => $templateConfig['col_7'],
                            ];
                        }
                    }
                }
            }
        }
        return $this->templates;
    }

    /**
     * Enable log to file
     *
     * @return int
     */
    public function getEnableLog()
    {
        return $this->getConfig(self::CONFIG_PATH . self::ENABLE_LOG);
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthBearer()
    {
        return $this->getConfig(self::CONFIG_PATH . self::BEARER);
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthBearerPath()
    {
        return self::CONFIG_PATH . self::BEARER;
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthRefreshTokenPath()
    {
        return self::CONFIG_PATH . self::REFRESH_TOKEN;
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthClientId()
    {
        return $this->getConfig(self::CONFIG_PATH . self::CLIENT_ID);
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthClientSecret()
    {
        return $this->getConfig(self::CONFIG_PATH . self::CLIENT_SECRET);
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->getConfig(self::CONFIG_PATH . self::REFRESH_TOKEN);
    }

    /**
     * Get Is Module enabled
     *
     * @return int
     */
    public function getOauthEnable()
    {
        return $this->getConfig(self::CONFIG_PATH . self::OAUTH_ENABLE);
    }

    /**
     * Get Is Module enabled
     *
     * @return int
     */
    public function getTokenEnable()
    {
        return $this->getConfig(self::CONFIG_PATH . self::TOKEN_ENABLE);
    }


}
