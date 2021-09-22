<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Api;

/**
 * Interface SmsapiCodeManagementInterface
 * @package Smsapi\Smsapi2\Api
 */
interface SmsapiCodeManagementInterface
{
    /**
     * GET for smsapiCode api
     * @param string $code
     * @return bool
     */
    public function getSmsapiCode(string $code): bool;
}
