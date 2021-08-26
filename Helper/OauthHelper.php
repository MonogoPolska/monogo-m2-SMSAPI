<?php

namespace Smsapi\Smsapi2\Helper;

use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class OauthHelper
 * @package Smsapi\Smsapi2\Helper
 */
class OauthHelper
{
    /**
     * @var Log
     */
    protected $log;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var DateTime
     */
    protected $datetime;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var StoreManagerInterface
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
     * @var UrlInterface
     */
    protected $urlBuilder;


    /**
     * OauthHelper constructor.
     * @param Log $log
     * @param Curl $curl
     * @param DateTime $datetime
     * @param Config $config
     * @param WriterInterface $configWriter
     * @param StoreManagerInterface $storeManager
     * @param TypeListInterface $cacheTypeList
     * @param Pool $cacheFrontendPool
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Log $log,
        Curl $curl,
        DateTime $datetime,
        Config $config,
        WriterInterface $configWriter,
        StoreManagerInterface $storeManager,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        UrlInterface $urlBuilder
    ) {
        $this->log = $log;
        $this->curl = $curl;
        $this->datetime = $datetime;
        $this->config = $config;
        $this->configWriter = $configWriter;
        $this->storeManager = $storeManager;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param $code
     * @return bool
     */
    public function authorize($code)
    {
        return $this->oauthGetToken($code);
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
            'scope' => 'sms,sms_sender,profile',
        ];
        $headers = [];
        $this->curl->write('POST', 'https://oauth.smsapi.io/api/oauth/token', '1.1', $headers, $authArray);
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


    /**
     * @return string
     */
    public function prepareRedirectUrl()
    {
        return $this->urlBuilder->getUrl('smsapi/oauth/callback');
    }

    /**
     * @param $value
     */
    public function setOauthBearer($value)
    {
        $this->configWriter->save($this->config->getOauthBearerPath(), $value,
            $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    /**
     * @param $value
     */
    public function setOauthRefreshToken($value)
    {
        $this->configWriter->save($this->config->getOauthRefreshTokenPath(), $value,
            $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    /**
     * @param $value
     */
    public function setOauthEnabled($value)
    {
        $this->configWriter->save($this->config->getOauthEnable(), $value,
            $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }


    /**
     *
     */
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

    /**
     * @return string
     */
    public function getOauthAuthorizationUrl()
    {
        $base = 'https://oauth.smsapi.io/oauth/access?';
        $params = [
            'client_id' => $this->config->getOauthClientId(),
            'redirect_uri' => $this->prepareRedirectUrl(),
            'scope' => 'sms,sms_sender,profile'
        ];
        return $base . http_build_query($params);
    }
}
