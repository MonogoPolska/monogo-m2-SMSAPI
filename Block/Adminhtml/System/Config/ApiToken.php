<?php
declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Smsapi\Client\Feature\Profile\Data\Profile;
use Smsapi\Client\Service\SmsapiComService;
use Smsapi\Client\Service\SmsapiPlService;
use Smsapi\Smsapi2\Helper\Config;
use Smsapi\Smsapi2\Helper\OauthHelper;
use Smsapi\Smsapi2\Model\Api\Client;

/**
 * Class ApiToken
 * @package Smsapi\Smsapi2\Block\Adminhtml\System\Config
 */
class ApiToken extends Field
{

    protected $_template = 'Smsapi_Smsapi2::system/config/apitoken.phtml';

    /**
     * @var Config
     */
    private $config;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var OauthHelper
     */
    private $oauthHelper;

    /**
     * ApiToken constructor.
     * @param Context $context
     * @param Config $config
     * @param Client $client
     * @param OauthHelper $oauthHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Client $client,
        OauthHelper $oauthHelper,
        array $data = []
    ) {
        $this->config = $config;
        $this->client = $client;
        $this->oauthHelper = $oauthHelper;
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Get Is Valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->config->validateCredentials() && $this->client->ping();
    }

    /**
     * Get current profile
     * @return Profile|null
     */
    public function getProfile(): ?Profile
    {
        return $this->client->getProfile();
    }

    /**
     * Get Last errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->client->getErrors();
    }

    /**
     * @return SmsapiComService|SmsapiPlService|string
     */
    public function getService()
    {
        return $this->config->getService();
    }

    /**
     * Get Is Valid
     *
     * @return bool
     */
    public function isOauthEnabled(): bool
    {
        return (bool)$this->config->getOauthEnable();
    }

    /**
     * Get Is Valid
     *
     * @return bool
     */
    public function isTokenEnabled(): bool
    {
        return (bool)$this->config->getTokenEnable();
    }

    /**
     * @return string
     */
    public function getOauthUrl(): string
    {
        return (string)$this->oauthHelper->getOauthAuthorizationUrl();
    }

    /**
     * @return string
     */
    public function isBearerSet(): string
    {
        return $this->config->getOauthBearer();
    }

    /**
     * @return bool
     */
    public function isTokenSet(): bool
    {
        return !empty($this->config->getApiToken());
    }
}
