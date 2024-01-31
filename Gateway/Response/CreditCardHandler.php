<?php

namespace BetterPayment\Core\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObject;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class CreditCardHandler implements HandlerInterface
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

        // Make sure the transaction is marked as pending so we don't get the wrong order state.
        $payment->setIsTransactionPending(true);
        $payment->setTransactionId($response['transaction_id']);

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        // Don't send the email just yet.
        $order->setCanSendNewEmailFlag(false);
    }
}
