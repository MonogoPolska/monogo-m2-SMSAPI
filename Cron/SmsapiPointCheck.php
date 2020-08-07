<?php

namespace Smsapi\Smsapi2\Cron;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Smsapi\Smsapi2\Helper\Config;
use Smsapi\Smsapi2\Helper\Log;
use Smsapi\Smsapi2\Model\Api\Client;

/**
 * Class SmsapiPointCheck
 *
 * @package Smsapi\Smsapi2\Cron
 */
class SmsapiPointCheck
{
    /**
     * @param Log $logger Log
     */
    protected $logger;

    /**
     * @param Config $config Config
     */
    protected $config;

    /**
     * @param Client $client Client
     */
    protected $client;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var File
     */
    protected $_file;

    /**
     * SmsapiPointCheck constructor.
     *
     * @param Log        $logger     Log
     * @param Config     $config     Config
     * @param Client     $client     Client
     * @param Filesystem $filesystem Filesystem
     * @param File       $file       File
     */
    public function __construct(
        Log $logger,
        Config $config,
        Client $client,
        Filesystem $filesystem,
        File $file
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->client = $client;
        $this->_filesystem = $filesystem;
        $this->_file = $file;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $fileName = "smsapi_limit";
        $varRootDir = $this->_filesystem->getDirectoryRead(
            DirectoryList::VAR_DIR
        )->getAbsolutePath();
        if ($this->isLimitExceeded()) {
            $this->_file->touch($varRootDir . $fileName);
        } else {
            $this->_file->deleteFile($varRootDir . $fileName);
        }
        $this->logger->addInfo("Cronjob smsapiPointCheck is executed. ");
    }

    /**
     * Check is limit exceeded
     *
     * @return bool
     */
    public function isLimitExceeded()
    {
        if ($this->isValid() && ($this->getProfile()->points <= $this->config->getAlertLimit())) {
            return true;
        }
        return false;
    }

    /**
     * Get Is Valid
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->config->validateCredentials() && $this->client->ping()->smsapi;
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
}
