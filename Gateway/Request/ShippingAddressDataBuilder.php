<?php

namespace BetterPayment\Core\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class ShippingAddressDataBuilder implements BuilderInterface
{
    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        /** @var PaymentDataObjectInterface $paymentData */
        $paymentData = $buildSubject['payment'];
        $order = $paymentData->getOrder();
        $shippingAddress = $order->getShippingAddress();

        return [
            // Street address
            'shipping_address' => $shippingAddress->getStreetLine1(),
            // Second address line
            'shipping_address2' => $shippingAddress->getStreetLine2(),
            // Name of the company of the given shipping address
            'shipping_company' => $shippingAddress->getCompany(),
            // The town, district or city of the shipping address
            'shipping_city' => $shippingAddress->getCity(),
            // The postal code or zip code of the shipping address
            'shipping_postal_code' => $shippingAddress->getPostcode(),
            // The county, state or region of the shipping address
            'shipping_state' => $shippingAddress->getRegionCode(),
            // Country Code in ISO 3166-1 alpha2
            'shipping_country' => $shippingAddress->getCountryId(),
            // Customer’s first name
            'shipping_first_name' => $shippingAddress->getFirstname(),
            // Customer’s last name
            'shipping_last_name' => $shippingAddress->getLastname(),
        ];
    }
}
