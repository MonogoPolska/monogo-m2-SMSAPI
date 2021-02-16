<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Api;

interface SmsapiCodeManagementInterface
{
    /**
     * GET for smsapiCode api
     * @param  string $code
     * @return string
     */
    public function getSmsapiCode($code);
}
