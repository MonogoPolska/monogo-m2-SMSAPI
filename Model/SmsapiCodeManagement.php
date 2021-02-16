<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smsapi\Smsapi2\Model;

class SmsapiCodeManagement implements \Smsapi\Smsapi2\Api\SmsapiCodeManagementInterface
{
    protected $oauthHelper;

    public function __construct(\Smsapi\Smsapi2\Helper\OauthHelper $oauthHelper)
    {
        $this->oauthHelper = $oauthHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getSmsapiCode($code)
    {
//        $auth = $this->oauthHelper->authorize($code);
        if ($this->oauthHelper->authorize($code) === false) {
            return 'Authorization error, try again';
        }
        return 'Authorized, please refresh admin panel';
    }
}
