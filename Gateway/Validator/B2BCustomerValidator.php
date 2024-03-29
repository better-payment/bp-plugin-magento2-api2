<?php

namespace BetterPayment\Core\Gateway\Validator;


use BetterPayment\Core\Util\PaymentMethod;
use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Quote\Model\Quote\Payment;

class B2BCustomerValidator extends AbstractValidator
{

    /**
     * @inheritDoc
     */
    public function validate(array $validationSubject)
    {
        $isValid = true;
        $fails = [];

        /** @var Payment $payment */
        $payment = $validationSubject['payment'];
        $paymentMethod = $payment->getMethod();
        $quote = $payment->getQuote();

        if ($quote) {
            $company = $quote->getBillingAddress()->getCompany();
            $isB2BCustomer = !empty($company);

            $allowedPaymentMethods = $isB2BCustomer ?
                [PaymentMethod::SEPA_DIRECT_DEBIT_B2B, PaymentMethod::INVOICE_B2B] :
                [PaymentMethod::SEPA_DIRECT_DEBIT, PaymentMethod::INVOICE];

            if (!in_array($paymentMethod, $allowedPaymentMethods)) {
                $isValid = false;
                $fails[] = $isB2BCustomer ? 'You can pay by B2B type payment method.' : 'You can pay by B2C type payment method.';
            }
        }

        return $this->createResult($isValid, $fails);
    }
}
