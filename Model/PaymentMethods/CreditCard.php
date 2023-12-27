<?php

namespace BetterPayment\Core\Model\PaymentMethods;

use Magento\Payment\Model\Method\Adapter;

class CreditCard extends Adapter
{
    const CODE = 'betterpayment_credit_card';
}
