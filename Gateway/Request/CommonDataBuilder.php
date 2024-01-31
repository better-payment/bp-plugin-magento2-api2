<?php

namespace BetterPayment\Core\Gateway\Request;

use BetterPayment\Core\Util\ConfigReader;
use BetterPayment\Core\Util\PaymentMethod;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Locale\Resolver;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class CommonDataBuilder implements BuilderInterface
{
    private ConfigReader $configReader;
    private Resolver $localeResolver;
    private ProductMetadataInterface $productMetadata;

    public function __construct(
        ConfigReader $configReader,
        Resolver $localeResolver,
        ProductMetadataInterface $productMetadata,
    ) {
        $this->configReader = $configReader;
        $this->localeResolver = $localeResolver;
        $this->productMetadata = $productMetadata;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        /** @var PaymentDataObjectInterface $paymentData */
        $paymentData = $buildSubject['payment'];

        /** @var OrderPaymentInterface $payment */
        $payment = $paymentData->getPayment();
        $order = $paymentData->getOrder();

        return [
            // payment method shortcode
            'payment_type' => PaymentMethod::SHORTCODE[$payment->getMethod()],
            // always enabled
            'risk_check_approval' => '1',
            // The URL for updates about transaction status are posted
            'postback_url' => $this->configReader->getWebhookUrl(),
            // Any alphanumeric string to identify the Merchant’s order
            'order_id' => $order->getOrderIncrementId(),
            // Any alphanumeric string to provide the customer number of a Merchant’s order (up to 40 characters) for factoring or debt collection
            'customer_id' => $order->getCustomerId(),
            // See details about merchant reference - https://testdashboard.betterpayment.de/docs/#merchant-reference
            'merchant_reference' => $order->getOrderIncrementId() . ' - ' . $this->configReader->get(ConfigReader::STORE_NAME),
            // Including possible shipping costs and VAT (float number)
            'amount' => $order->getGrandTotalAmount(),
            // Should be set if the order includes any shipping costs (float number)
            'shipping_costs' => $payment->getShippingAmount(),
            // VAT amount (float number) if known
            // TODO: fetch TAX/VAT
            'vat' => '',
            // 3-letter currency code (ISO 4217). Defaults to ‘EUR’
            'currency' => $order->getCurrencyCode(),
            // If the order includes a risk check, this field can be set to prevent customers from making multiple order attempts with different personal information.
            'customer_ip' => $order->getRemoteIp(),
            // The language of payment forms in Credit Card and Paypal. Possible locale values - https://testdashboard.betterpayment.de/docs/#locales
            // use substr to convert en_US to en
            'locale' => substr($this->localeResolver->getLocale(), 0, 2),
            // module/plugin metadata
            'app_name' => 'Magento 2',
            'app_version' => 'Magento ' . $this->productMetadata->getVersion() . ', Plugin ' . $this->configReader->getModuleVersion(),
        ];
    }
}
