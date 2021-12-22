<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package Smsapi\Smsapi2\Helper
 */
class Config extends AbstractHelper
{
    public const CONFIG_PATH = 'smsapi/';

    public const ENABLED = 'general/enable';

    public const API_TOKEN = 'general/apitoken';

    public const ALLOW_LONG = 'general/allow_long';

    public const STORE_NAME = 'general/storename';

    public const SENDER = 'general/sender';

    public const ALERT_LIMIT = 'general/alert_limit';

    public const SERVICE = 'general/service_location';

    public const CC_NUMBERS = 'general/ccnumbers';

    public const TEMPLATES = 'templates/messages';

    public const ENABLE_LOG = 'debug/enable_log';

    public const BEARER = 'oauth/bearer';

    public const CLIENT_ID = 'oauth/client_id';

    public const CLIENT_SECRET = 'oauth/client_secret';

    public const REFRESH_TOKEN = 'oauth/refresh_token';

    public const OAUTH_ENABLE = 'general/service_enabled';

    public const TOKEN_ENABLE = 'general/service_enabled';

    protected $templates;

    /**
     * Get Store Config by key
     *
     * @param string $config_path Path
     *
     * @return mixed
     */
    public function getConfig(string $config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Store Config by key
     * @param string $config_path
     * @param $value
     * @return mixed
     */
    public function setConfig(string $config_path, $value)
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
    public function getIsEnabled(): int
    {
        return (int)$this->getConfig(self::CONFIG_PATH . self::ENABLED);
    }

    /**
     * Get API User
     *
     * @return string
     */
    public function getApiToken(): string
    {
        return (string)$this->getConfig(self::CONFIG_PATH . self::API_TOKEN);
    }

    /**
     * Check if credentials are not empty
     *
     * @return bool
     */
    public function validateCredentials(): bool
    {
        return !empty($this->getApiToken());
    }

    /**
     * Get Allow long messages
     *
     * @return int
     */
    public function getAllowLong(): int
    {
        return (int)$this->getConfig(self::CONFIG_PATH . self::ALLOW_LONG);
    }

    /**
     * Get Store Name in message
     *
     * @return string
     */
    public function getStoreName(): string
    {
        return (string)$this->getConfig(self::CONFIG_PATH . self::STORE_NAME);
    }

    /**
     * Get Sender
     *
     * @return string
     */
    public function getSender(): string
    {
        return (string)$this->getConfig(self::CONFIG_PATH . self::SENDER);
    }

    /**
     * Get Alert limit
     *
     * @return int
     */
    public function getAlertLimit(): int
    {
        return (int)$this->getConfig(self::CONFIG_PATH . self::ALERT_LIMIT);
    }

    /**
     * Get Service
     *
     * @return string
     */
    public function getService(): string
    {
        return (string)$this->getConfig(self::CONFIG_PATH . self::SERVICE);
    }

    /**
     * Get Service
     *
     * @return array
     */
    public function getCcNumbers(): array
    {
        return array_unique(explode(',', $this->getConfig(self::CONFIG_PATH . self::CC_NUMBERS)));
    }

    /**
     * Get SMS Templates
     * @return mixed
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
    public function getEnableLog(): int
    {
        return (int)$this->getConfig(self::CONFIG_PATH . self::ENABLE_LOG);
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthBearer(): string
    {
        return (string)$this->getConfig(self::CONFIG_PATH . self::BEARER);
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthBearerPath(): string
    {
        return self::CONFIG_PATH . self::BEARER;
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthRefreshTokenPath(): string
    {
        return self::CONFIG_PATH . self::REFRESH_TOKEN;
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthClientId(): string
    {
        return (string)$this->getConfig(self::CONFIG_PATH . self::CLIENT_ID);
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getOauthClientSecret(): string
    {
        return (string)$this->getConfig(self::CONFIG_PATH . self::CLIENT_SECRET);
    }

    /**
     * Get Is Module enabled
     *
     * @return string
     */
    public function getRefreshToken(): string
    {
        return (string)$this->getConfig(self::CONFIG_PATH . self::REFRESH_TOKEN);
    }

    /**
     * Get Is Module enabled
     *
     * @return int
     */
    public function getOauthEnable(): int
    {
        return (int)($this->getConfig(self::CONFIG_PATH . self::OAUTH_ENABLE) === "oauth");
    }

    /**
     * Get Is Module enabled
     *
     * @return int
     */
    public function getTokenEnable(): int
    {
        return (int)($this->getConfig(self::CONFIG_PATH . self::TOKEN_ENABLE) === "apitoken");
    }
}
