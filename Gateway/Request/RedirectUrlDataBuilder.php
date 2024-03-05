<?php

namespace BetterPayment\Core\Gateway\Request;

use BetterPayment\Core\Util\PaymentMethod;
use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class RedirectUrlDataBuilder implements BuilderInterface
{
    private UrlInterface $url;

    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
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

        return in_array($payment->getMethod(), PaymentMethod::ASYNC_PAYMENT_METHODS) ? [
            'success_url' => $this->url->getUrl('redirect/successurl'),
            'error_url' => $this->url->getUrl('redirect/errorurl'),
        ] : [];
    }
}
