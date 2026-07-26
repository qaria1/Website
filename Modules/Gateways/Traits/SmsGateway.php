<?php

namespace Modules\Gateways\Traits;

use App\Traits\SmsGateway as AppSmsGateway;

trait SmsGateway
{
    use AppSmsGateway;

    public static function send($receiver, $otp): string
    {
        return AppSmsGateway::send($receiver, $otp);
    }
}
