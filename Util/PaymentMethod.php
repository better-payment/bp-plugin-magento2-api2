<?php

namespace BetterPayment\Core\Util;

class PaymentMethod
{
    public const CREDIT_CARD = 'betterpayment_credit_card';
    public const GIROPAY = 'betterpayment_giropay';
    public const PAYDIREKT = 'betterpayment_paydirekt';
    public const SOFORT = 'betterpayment_sofort';
    public const REQUEST_TO_PAY = 'betterpayment_request_to_pay';
    public const PAYPAL = 'betterpayment_paypal';
    public const AIIA = 'betterpayment_aiia';
    public const IDEAL = 'betterpayment_ideal';
    public const SEPA_DIRECT_DEBIT = 'betterpayment_sepa_direct_debit';
    public const SEPA_DIRECT_DEBIT_B2B = 'betterpayment_sepa_direct_debit_b2b';
    public const INVOICE = 'betterpayment_invoice';
    public const INVOICE_B2B = 'betterpayment_invoice_b2b';

    public const ASYNC_PAYMENT_METHODS = [
        self::CREDIT_CARD,
        self::GIROPAY,
        self::PAYDIREKT,
        self::SOFORT,
        self::REQUEST_TO_PAY,
        self::PAYPAL,
        self::AIIA,
        self::IDEAL,
    ];

    public const SHORTCODE = [
        self::CREDIT_CARD => 'cc',
        self::GIROPAY => 'giro',
        self::PAYDIREKT => 'paydirekt',
        self::SOFORT => 'sofort',
        self::REQUEST_TO_PAY => 'rtp',
        self::PAYPAL => 'paypal',
        self::AIIA => 'aiia',
        self::IDEAL => 'ideal',
        self::SEPA_DIRECT_DEBIT => 'dd',
        self::SEPA_DIRECT_DEBIT_B2B => 'dd_b2b',
        self::INVOICE => 'kar',
        self::INVOICE_B2B => 'kar_b2b',
    ];
}
