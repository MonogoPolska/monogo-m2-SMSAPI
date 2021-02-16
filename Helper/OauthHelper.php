<?php

namespace Smsapi\Smsapi2\Helper;

use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class OauthHelper extends AbstractHelper
{
    /**
     * @var \Smsapi\Smsapi2\Helper\Log
     */
    protected $log;

    /**
     * @var \Magento\Framework\HTTP\Adapter\Curl
     */
    protected $curl;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $datetime;

    /**
     * @var \Smsapi\Smsapi2\Helper\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $configWriter;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var Pool
     */
    protected $cacheFrontendPool;

    /**
     * Constructor
     *
     * @param \Smsapi\Smsapi2\Helper\Log                  $log
     * @param \Magento\Framework\HTTP\Adapter\Curl        $curl
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $datetime
     */
    public function __construct(
        \Smsapi\Smsapi2\Helper\Log $log,
        \Magento\Framework\HTTP\Adapter\Curl $curl,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Smsapi\Smsapi2\Helper\Config $config,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        $this->log = $log;
        $this->curl = $curl;
        $this->datetime = $datetime;
        $this->config = $config;
        $this->configWriter = $configWriter;
        $this->storeManager = $storeManager;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
    }

    public function authorize($code)
    {
        $this->oauthGetToken($code);
    }

    /**
     * @param $code
     * @return bool
     */
    public function oauthGetToken($code)
    {
        $authArray = [
            'client_id' => $this->config->getOauthClientId(),
            'client_secret' => $this->config->getOauthClientSecret(),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->prepareRedirectUrl(),
            'scope' => 'sms',
        ];
        $headers = [];
        $this->curl->write('POST', 'https://ssl.smsapi.pl/api/oauth/token', '1.1', $headers, $authArray);
        $response = $this->curl->read();
        if ($response) {
            $responseArray = explode("\n", $response);
            $body = array_slice($responseArray, -1);
            $json = json_decode($body[0]);
            if (isset($json->access_token)) {
                $bearer = json_decode(json_encode($json->access_token), true);
                $refreshToken = json_decode(json_encode($json->refresh_token), true);
                $this->log->log('Bearer ' . $bearer . ' Refresh token ' . $refreshToken);
                $this->setOauthBearer($bearer);
                $this->setOauthRefreshToken($refreshToken);
                $this->setOauthEnabled(true);
                $this->flushCacheCache();
                return true;
            }
        }
        return false;
    }

    public function setOauthBearer($value)
    {
        $this->configWriter->save($this->config->getOauthBearerPath(), $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setOauthRefreshToken($value)
    {
        $this->configWriter->save($this->config->getOauthRefreshTokenPath(), $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setOauthEnabled($value)
    {
        $this->configWriter->save($this->config->getOauthEnable(), $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function prepareRedirectUrl()
    {
        $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_DIRECT_LINK);
        $endpointUrl = 'rest/all/V1/smsapi-smsapi2/smsapicode/';
        return sprintf('%s%s', $storeUrl, $endpointUrl);
    }

    public function flushCacheCache()
    {
        $_types = [
            'config',
        ];

        foreach ($_types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }

    public function getOauthAuthorizationUrl()
    {
        $base = 'https://ssl.smsapi.pl/oauth/access?';
        $params = ['client_id' => $this->config->getOauthClientId(), 'redirect_uri' => $this->prepareRedirectUrl(),'scope'=>'sms'];
        return $base . http_build_query($params);
    }
}
