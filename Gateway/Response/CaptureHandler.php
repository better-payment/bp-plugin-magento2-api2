<?php

namespace BetterPayment\Core\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObject;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class CaptureHandler implements HandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(array $handlingSubject, array $response)
    {
        /** @var PaymentDataObject $paymentDataObject */
        $paymentDataObject = $handlingSubject['payment'];
        /** @var OrderPaymentInterface $payment */
        $payment = $paymentDataObject->getPayment();

        $payment->setTransactionId($response['transaction_id']);
    }
}
