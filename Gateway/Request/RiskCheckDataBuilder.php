<?php

namespace BetterPayment\Core\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class RiskCheckDataBuilder implements BuilderInterface
{
    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        // TODO: to be improved
        return [
            'gender' => 'm',
            'date_of_birth' => '2000-01-01'
        ];
    }
}
