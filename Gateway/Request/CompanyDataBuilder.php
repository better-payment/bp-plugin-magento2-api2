<?php

namespace BetterPayment\Core\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class CompanyDataBuilder implements BuilderInterface
{
    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        /** @var PaymentDataObjectInterface $paymentData */
        $paymentData = $buildSubject['payment'];
        $order = $paymentData->getOrder();

        // TODO: to be improved as in SHOPWARE plugin company detail parameters
        return [
            // Company name
            'company' => $order->getBillingAddress()->getCompany(),
            // Starts with ISO 3166-1 alpha2 followed by 2 to 11 characters. See more details about Vat - http://ec.europa.eu/taxation_customs/vies/
            'company_vat_id' => '',
        ];
    }
}
