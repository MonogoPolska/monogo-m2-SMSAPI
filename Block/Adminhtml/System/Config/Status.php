<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
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

    protected $oauthHelper = null;

    /**
     * Status constructor.
     *
     * @param Context     $context
     * @param Config      $config
     * @param Client      $client
     * @param OauthHelper $oauthHelper
     * @param array       $data
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
    public function render(AbstractElement $element)
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
    public function isValid()
    {
        return $this->config->validateCredentials() && $this->client->ping();
    }

    /**
     * Get current profile
     *
     * @return \Smsapi\Client\Feature\Profile\Data\Profile
     */
    public function getProfile()
    {
        return $this->client->getProfile();
    }

    /**
     * Get Last errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->client->getErrors();
    }

    public function getService()
    {
        return $this->config->getService();
    }

    /**
     * Get Is Valid
     *
     * @return bool
     */
    public function isOauthEnabled()
    {
        return $this->config->getOauthEnable();
    }

    public function getOauthUrl()
    {
        return (string)$this->oauthHelper->getOauthAuthorizationUrl();
    }
}
