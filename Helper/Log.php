<?php

namespace Smsapi\Smsapi2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Smsapi\Smsapi2\Logger\Logger;

/**
 * Log helper
 *
 * @category SMSAPI
 * @package  Smsapi|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Log extends AbstractHelper
{
    /**
     * @var Logger
     */
    protected $logger = null;

    /**
     * @var Config
     */
    protected $config = null;

    /**
     * Log constructor.
     * @param Context                       $context
     * @param Logger                        $logger
     * @param \Smsapi\Smsapi2\Helper\Config $config
     */
    public function __construct(
        Context $context,
        Logger $logger,
        \Smsapi\Smsapi2\Helper\Config $config
    ) {
        $this->logger = $logger;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Get logger
     *
     * @return null|Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * Log to file
     *
     * @param string|array $message Message
     *
     * @return void
     */
    public function log($message)
    {
        $enabled
            = $this->config->getEnableLog();
        if ($enabled) {
            $this->getLogger()->info(print_r($message, true));
        }
    }
}
