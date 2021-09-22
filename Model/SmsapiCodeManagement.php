<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smsapi\Smsapi2\Model;

use Smsapi\Smsapi2\Api\SmsapiCodeManagementInterface;
use Smsapi\Smsapi2\Helper\OauthHelper;

/**
 * Class SmsapiCodeManagement
 * @package Smsapi\Smsapi2\Model
 */
class SmsapiCodeManagement implements SmsapiCodeManagementInterface
{
    /**
     * @var OauthHelper
     */
    protected $oauthHelper;

    /**
     * SmsapiCodeManagement constructor.
     * @param OauthHelper $oauthHelper
     */
    public function __construct(OauthHelper $oauthHelper)
    {
        $this->oauthHelper = $oauthHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getSmsapiCode(string $code): bool
    {
        return $this->oauthHelper->authorize($code);
    }
}
