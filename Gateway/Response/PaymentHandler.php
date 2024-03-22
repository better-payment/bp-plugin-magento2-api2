<?php

namespace BetterPayment\Core\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObject;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class PaymentHandler implements HandlerInterface
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

        // Make sure the transaction is marked as pending, so we don't get the wrong order state.
        $payment->setIsTransactionPending(true);
        // Don't close the transaction yet, so it can be refunded later
        $payment->setIsTransactionClosed(false);
        $payment->setTransactionId($response['transaction_id']);

        if (isset($response['action_data']['url'])) {
            $payment->setAdditionalInformation('redirect_url', $response['action_data']['url']);

            /** @var OrderInterface $order */
            $order = $payment->getOrder();

            // Don't send the email just yet.
            $order->setCanSendNewEmailFlag(false);
        }
    }
}
