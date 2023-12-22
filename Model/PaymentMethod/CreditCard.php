<?php

namespace BetterPayment\Core\Model\PaymentMethod;

use Magento\Payment\Model\Method\Adapter;

class CreditCard extends Adapter
{
    const CODE = 'betterpayment_credit_card';

    public function getCode(): string
    {
        return self::CODE;
    }
}
