<?php

namespace Monogo\Smsapi\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Monogo\Smsapi\Helper\Config;
use Monogo\Smsapi\Helper\Log;
use Monogo\Smsapi\Model\Api\Client;

/**
 * Sender source model
 *
 * @category SMSAPI
 * @package  Monogo|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Sender implements OptionSourceInterface
{
    /**
     * Option Array
     *
     * @var array
     */
    protected $optionArray = [];

    /**
     * Logger Helper
     *
     * @var Log
     */
    protected $log = null;

    /**
     * Config Helper
     *
     * @var Config
     */
    protected $config = null;

    /**
     * API Client
     *
     * @var Client
     */
    protected $client = null;

    /**
     * Sender constructor.
     *
     * @param Log    $log
     * @param Config $config
     * @param Client $client
     */
    public function __construct(
        Log $log,
        Config $config,
        Client $client
    ) {
        $this->log = $log;
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (empty($this->optionArray)) {
            $senders = $this->client->getSenders();
            if (!empty($senders)) {
                foreach ($senders as $sender) {
                    if ($sender->status == 'ACTIVE') {
                        $this->optionArray[] = ['value' => $sender->sender, 'label' => $sender->sender];
                    }
                }
            } else {
                $this->optionArray = [['value' => 'auth_failed', 'label' => __('Please provide valid Token and save')]];
            }
        }
        return $this->optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $optionArray = [];
        foreach ($this->toOptionArray() as $option) {
            $optionArray[$option['value']] = $option['label'];
        }
        return $optionArray;
    }
}
