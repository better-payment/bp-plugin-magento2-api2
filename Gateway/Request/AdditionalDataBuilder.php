<?php

namespace BetterPayment\Core\Gateway\Request;

use BetterPayment\Core\Observer\PaymentMethodDataAssignObserver;
use BetterPayment\Core\Util\PaymentMethod;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class AdditionalDataBuilder implements BuilderInterface
{
    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        /** @var PaymentDataObjectInterface $paymentData */
        $paymentData = $buildSubject['payment'];

        /** @var OrderPaymentInterface $payment */
        $payment = $paymentData->getPayment();

        return match ($payment->getMethod()) {
            PaymentMethod::SEPA_DIRECT_DEBIT, PaymentMethod::SEPA_DIRECT_DEBIT_B2B => [
                'account_holder' => $payment->getAdditionalInformation(PaymentMethodDataAssignObserver::ACCOUNT_HOLDER),
                'iban' => $payment->getAdditionalInformation(PaymentMethodDataAssignObserver::IBAN),
                'bic' => $payment->getAdditionalInformation(PaymentMethodDataAssignObserver::BIC),
                'sepa_mandate' => $payment->getAdditionalInformation(PaymentMethodDataAssignObserver::SEPA_MANDATE),
            ],
            // add other payment method specific additional data here
            default => [],
        };
    }
}
