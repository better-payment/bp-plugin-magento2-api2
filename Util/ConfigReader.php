<?php

namespace BetterPayment\Core\Util;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigReader
{
    public const STORE_NAME = 'general/store_information/name';

    public const ENVIRONMENT = 'betterpayment/base_configuration/environment';
    public const TEST_API_URL = 'betterpayment/test_environment_credentials/testApiUrl';
    public const TEST_API_KEY = 'betterpayment/test_environment_credentials/testApiKey';
    public const TEST_OUTGOING_KEY = 'betterpayment/test_environment_credentials/testOutgoingKey';
    public const TEST_INCOMING_KEY = 'betterpayment/test_environment_credentials/testIncomingKey';
    public const PRODUCTION_API_URL = 'betterpayment/production_environment_credentials/productionApiUrl';
    public const PRODUCTION_API_KEY = 'betterpayment/production_environment_credentials/productionApiKey';
    public const PRODUCTION_OUTGOING_KEY = 'betterpayment/production_environment_credentials/productionOutgoingKey';
    public const PRODUCTION_INCOMING_KEY = 'betterpayment/production_environment_credentials/productionIncomingKey';
    public const SEPA_DIRECT_DEBIT_CREDITOR_ID = 'payment/betterpayment_sepa_direct_debit/sepaDirectDebitCreditorID';
    public const SEPA_DIRECT_DEBIT_COMPANY_NAME = 'payment/betterpayment_sepa_direct_debit/sepaDirectDebitCompanyName';
    public const SEPA_DIRECT_DEBIT_COLLECT_DATE_OF_BIRTH = 'payment/betterpayment_sepa_direct_debit/sepaDirectDebitCollectDateOfBirth';
    public const SEPA_DIRECT_DEBIT_COLLECT_GENDER = 'payment/betterpayment_sepa_direct_debit/sepaDirectDebitCollectGender';
    public const SEPA_DIRECT_DEBIT_RISK_CHECK_AGREEMENT = 'payment/betterpayment_sepa_direct_debit/sepaDirectDebitRiskCheckAgreement';

    public const SEPA_DIRECT_DEBIT_B2B_CREDITOR_ID = 'payment/betterpayment_sepa_direct_debit_b2b/sepaDirectDebitB2BCreditorID';
    public const SEPA_DIRECT_DEBIT_B2B_COMPANY_NAME = 'payment/betterpayment_sepa_direct_debit_b2b/sepaDirectDebitB2BCompanyName';
    public const SEPA_DIRECT_DEBIT_B2B_RISK_CHECK_AGREEMENT = 'payment/betterpayment_sepa_direct_debit_b2b/sepaDirectDebitB2BRiskCheckAgreement';

    public const INVOICE_DISPLAY_INSTRUCTION = 'payment/betterpayment_invoice/invoiceDisplayInstruction';
    public const INVOICE_IBAN = 'payment/betterpayment_invoice/invoiceIBAN';
    public const INVOICE_BIC = 'payment/betterpayment_invoice/invoiceBIC';
    public const INVOICE_COLLECT_DATE_OF_BIRTH = 'payment/betterpayment_invoice/invoiceCollectDateOfBirth';
    public const INVOICE_COLLECT_GENDER = 'payment/betterpayment_invoice/invoiceCollectGender';
    public const INVOICE_RISK_CHECK_AGREEMENT = 'payment/betterpayment_invoice/invoiceRiskCheckAgreement';
    public const INVOICE_AUTOMATICALLY_CAPTURE_ON_ORDER_INVOICE_DOCUMENT_SENT = 'payment/betterpayment_invoice/invoiceAutomaticallyCaptureOnOrderInvoiceDocumentSent';

    public const INVOICE_B2B_DISPLAY_INSTRUCTION = 'payment/betterpayment_invoice_b2b/invoiceB2BDisplayInstruction';
    public const INVOICE_B2B_IBAN = 'payment/betterpayment_invoice_b2b/invoiceB2BIBAN';
    public const INVOICE_B2B_BIC = 'payment/betterpayment_invoice_b2b/invoiceB2BBIC';
    public const INVOICE_B2B_RISK_CHECK_AGREEMENT = 'payment/betterpayment_invoice_b2b/invoiceB2BRiskCheckAgreement';
    public const INVOICE_B2B_AUTOMATICALLY_CAPTURE_ON_ORDER_INVOICE_DOCUMENT_SENT = 'payment/betterpayment_invoice_b2b/invoiceB2BAutomaticallyCaptureOnOrderInvoiceDocumentSent';

    private ScopeConfigInterface $scopeConfig;
    private UrlInterface $url;
    private ComponentRegistrarInterface $componentRegistrar;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        UrlInterface $url,
        ComponentRegistrarInterface $componentRegistrar,
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
        $this->componentRegistrar = $componentRegistrar;
    }

    public function get(string $path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    // Get final part of config key which is defined in system.xml as field id
    public function getConfigId(string $configPath): string
    {
        $parts = explode("/", $configPath);
        return end($parts);
    }

    public function getWebhookUrl(): string
    {
        return $this->url->getUrl('rest/V1/betterpayment-webhook');
    }

    public function getModuleVersion(): string
    {
        $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'BetterPayment_Core');
        $composerJsonData = json_decode(file_get_contents($modulePath . '/composer.json'), true);

        return $composerJsonData['version'];
    }

    public function getApiUrl(): string
    {
        $apiUrl = $this->get(self::ENVIRONMENT) == 'test'
            ? $this->get(self::TEST_API_URL)
            : $this->get(self::PRODUCTION_API_URL);

        return rtrim($apiUrl, '/');
    }

    public function getApiKey(): string
    {
        return $this->get(self::ENVIRONMENT) == 'test'
            ? $this->get(self::TEST_API_KEY)
            : $this->get(self::PRODUCTION_API_KEY);
    }

    public function getOutgoingKey(): string
    {
        return $this->get(self::ENVIRONMENT) == 'test'
            ? $this->get(self::TEST_OUTGOING_KEY)
            : $this->get(self::PRODUCTION_OUTGOING_KEY);
    }

    public function getIncomingKey(): string
    {
        return $this->get(self::ENVIRONMENT) == 'test'
            ? $this->get(self::TEST_INCOMING_KEY)
            : $this->get(self::PRODUCTION_INCOMING_KEY);
    }

}
