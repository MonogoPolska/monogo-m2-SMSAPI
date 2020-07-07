<?php

namespace Monogo\Smsapi\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Log Handler
 *
 * @category SMSAPI
 * @package  Monogo|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Handler extends Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/smsapi.log';
}
