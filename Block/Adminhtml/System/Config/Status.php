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
 * Status Block
 *
 * @category SMSAPI
 * @package  Smsapi|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Status extends Field
{
    /**
     * @var Client
     */
    protected $config = null;

    /**
     * @var Config
     */
    protected $client = null;

    /**
     * @var OauthHelper|null
     */
    protected $oauthHelper = null;

    /**
     * Status constructor.
     *
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
     * Template path
     *
     * @var string
     */
    protected $_template = 'system/config/status.phtml';

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element Element
     *
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $columns = $this->getRequest()
            ->getParam('website') || $this->getRequest()->getParam('store') ? 5 : 4;
        return $this->_decorateRowHtml(
            $element,
            "<td colspan='{$columns}'>" . $this->toHtml() . '</td>'
        );
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
        return empty($this->config->getApiToken());
    }
}
