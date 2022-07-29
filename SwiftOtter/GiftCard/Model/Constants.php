<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model;

class Constants
{
    const OPTION_AMOUNT = 'amount';
    const OPTION_RECIPIENT_EMAIL = 'recipient_email';
    const OPTION_RECIPIENT_NAME = 'recipient_name';
    const OPTION_LIST = [
        self::OPTION_AMOUNT,
        self::OPTION_RECIPIENT_EMAIL,
        self::OPTION_RECIPIENT_NAME
    ];
    const TOTAL_NAME = 'total';
}
