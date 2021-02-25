<?php

namespace Smsapi\Smsapi2\Model\Message;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Notification\MessageInterface;
use Smsapi\Smsapi2\Helper\Config;

/**
 * Class SmsLimitExceedMessage
 *
 * @package Monogo\Smsapi\Model\Message
 */
class SmsLimitExceedMessage implements MessageInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var File
     */
    protected $_file;

    /**
     * SmsLimitExceedMessage constructor.
     *
     * @param Config     $config     Config
     * @param Filesystem $filesystem Filesystem
     * @param File       $file       File
     */
    public function __construct(
        Config $config,
        Filesystem $filesystem,
        File $file
    ) {
        $this->config = $config;
        $this->_filesystem = $filesystem;
        $this->_file = $file;
    }

    /**
     * @return bool|int
     */
    public function isDisplayed()
    {
        $varRootDir = $this->_filesystem->getDirectoryRead(DirectoryList::VAR_DIR)->getAbsolutePath();
        return $this->_file->isExists($varRootDir . 'smsapi_limit');
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return 'smsapi_smslimit_notification';
    }

    /**
     * Retrieve message text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getText()
    {
        return __('Points Limit Exceeded');
    }

    /**
     * @return int
     */
    public function getSeverity()
    {
        return self::SEVERITY_CRITICAL;
    }
}
