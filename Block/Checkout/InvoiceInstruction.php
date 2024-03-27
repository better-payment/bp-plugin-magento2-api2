<?php

namespace BetterPayment\Core\Block\Checkout;

use BetterPayment\Core\Util\ConfigReader;
use BetterPayment\Core\Util\PaymentMethod;
use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class InvoiceInstruction extends Template
{
    private Session $checkoutSession;
    private ConfigReader $configReader;

    public function __construct(
        Session $checkoutSession,
        ConfigReader $configReader,


        // Required by parent class
        Context $context,
        array $data = [],
    ) {
        parent::__construct($context, $data);

        $this->checkoutSession = $checkoutSession;
        $this->configReader = $configReader;
    }

    private function paymentMethod(): string
    {
        return $this->checkoutSession->getLastRealOrder()->getPayment()->getMethod();
    }

    public function isVisible(): bool
    {
        return ($this->paymentMethod() == PaymentMethod::INVOICE
                && $this->configReader->get(ConfigReader::INVOICE_DISPLAY_INSTRUCTION))
            || ($this->paymentMethod() == PaymentMethod::INVOICE_B2B
                && $this->configReader->get(ConfigReader::INVOICE_B2B_DISPLAY_INSTRUCTION));
    }

    public function iban(): string
    {
        if ($this->paymentMethod() == PaymentMethod::INVOICE) {
            return $this->configReader->get(ConfigReader::INVOICE_IBAN);
        }
        elseif ($this->paymentMethod() == PaymentMethod::INVOICE_B2B) {
            return $this->configReader->get(ConfigReader::INVOICE_B2B_IBAN);
        }

        return '';
    }

    public function bic(): string
    {
        if ($this->paymentMethod() == PaymentMethod::INVOICE) {
            return $this->configReader->get(ConfigReader::INVOICE_BIC);
        }
        elseif ($this->paymentMethod() == PaymentMethod::INVOICE_B2B) {
            return $this->configReader->get(ConfigReader::INVOICE_B2B_BIC);
        }

        return '';
    }

    public function reference(): string
    {
        return $this->checkoutSession->getLastRealOrder()->getIncrementId();
    }
}
