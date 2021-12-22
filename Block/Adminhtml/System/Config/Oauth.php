<?php
declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\System\Config;

/**
 * Class Oauth
 * @package Smsapi\Smsapi2\Block\Adminhtml\System\Config
 */
class Oauth extends ApiToken
{
    /**
     * Path to block template
     */
    protected $_template = 'Smsapi_Smsapi2::system/config/oauth.phtml';
}
