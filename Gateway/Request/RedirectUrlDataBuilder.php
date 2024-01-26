<?php

namespace BetterPayment\Core\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class RedirectUrlDataBuilder implements BuilderInterface
{
    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        return [
            'success_url' => '',
            'error_url' => '',
        ];
    }
}
