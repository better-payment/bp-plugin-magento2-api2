<?php

namespace BetterPayment\Core\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class BillingAddressDataBuilder implements BuilderInterface
{
    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        /** @var PaymentDataObjectInterface $paymentData */
        $paymentData = $buildSubject['payment'];
        $order = $paymentData->getOrder();
        $billingAddress = $order->getBillingAddress();

        return [
            // Street address
            'address' => $billingAddress->getStreetLine1(),
            // Second address line
            'address2' => $billingAddress->getStreetLine2(),
            // The town, district or city of the billing address
            'city' => $billingAddress->getCity(),
            // The postal code or zip code of the billing address
            'postal_code' => $billingAddress->getPostcode(),
            // The county, state or region of the billing address
            'state' => $billingAddress->getRegionCode(),
            // Country Code in ISO 3166-1
            'country' => $billingAddress->getCountryId(),
            // Customer’s first name
            'first_name' => $billingAddress->getFirstname(),
            // Customer’s last name
            'last_name' => $billingAddress->getLastname(),
            // Customer’s last email. We suggest to provide an email when transaction's payment method type is CC(credit card) to avoid declines in 3DS2.
            'email' => $billingAddress->getEmail(),
            // Customer’s phone number
            'phone' => $billingAddress->getTelephone(),
        ];
    }
}
