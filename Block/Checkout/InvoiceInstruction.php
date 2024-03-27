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

    /**
     * Initialize data and prepare it for output
     *
     * @return InvoiceInstruction
     */
    protected function _beforeToHtml(): InvoiceInstruction
    {
        $order = $this->checkoutSession->getLastRealOrder();

        $this->addData([
            'iban' => $this->configReader->get(ConfigReader::INVOICE_IBAN),
            'bic' => $this->configReader->get(ConfigReader::INVOICE_BIC),
            'reference' => $order->getIncrementId(),
        ]);

        return parent::_beforeToHtml();
    }

    public function isVisible(): bool
    {
        return $this->checkoutSession->getLastRealOrder()->getPayment()->getMethod() == PaymentMethod::INVOICE
            && $this->configReader->get(ConfigReader::INVOICE_DISPLAY_INSTRUCTION);
    }
}
