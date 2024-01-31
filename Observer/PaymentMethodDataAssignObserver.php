<?php

namespace BetterPayment\Core\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

class PaymentMethodDataAssignObserver extends AbstractDataAssignObserver
{
    public const ACCOUNT_HOLDER = 'betterpayment_account_holder';
    public const IBAN = 'betterpayment_iban';
    public const BIC = 'betterpayment_bic';
    public const SEPA_MANDATE = 'betterpayment_sepa_mandate';

    protected $additionalInformationList = [
        self::ACCOUNT_HOLDER,
        self::IBAN,
        self::BIC,
        self::SEPA_MANDATE
    ];

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $data = $this->readDataArgument($observer);
        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);

        if (!is_array($additionalData)) {
            return;
        }

        $paymentInfo = $this->readPaymentModelArgument($observer);
        foreach ($this->additionalInformationList as $additionalInformationKey) {
            if (isset($additionalData[$additionalInformationKey])) {
                $paymentInfo->setAdditionalInformation(
                    $additionalInformationKey,
                    $additionalData[$additionalInformationKey]
                );
            }
        }
    }
}
