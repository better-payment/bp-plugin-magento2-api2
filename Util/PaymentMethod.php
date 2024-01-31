<?php

namespace BetterPayment\Core\Util;

class PaymentMethod
{
    public const CREDIT_CARD = 'betterpayment_credit_card';
    public const SEPA_DIRECT_DEBIT = 'betterpayment_sepa_direct_debit';

    public const SHORTCODE = [
        self::CREDIT_CARD => 'cc',
        self::SEPA_DIRECT_DEBIT => 'dd'
    ];
}
