<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model;

class Constants
{
    const OPTION_AMMOUNT = 'ammount';
    const OPTION_RECIPIENT_EMAIL = 'recipient_email';
    const OPTION_RECIPIENT_NAME = 'recipient_name';
    const OPTION_LIST = [
        self::OPTION_AMMOUNT,
        self::OPTION_RECIPIENT_EMAIL,
        self::OPTION_RECIPIENT_NAME
    ];
}
