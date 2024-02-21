<?php

namespace BetterPayment\Core\Util;

use Magento\Framework\App\Config\ScopeConfigInterface;
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

    private ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
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
        // TODO: fetch dynamically
        return 'https://localhost/rest/V1/betterpayment/webhook';
    }

    public function getModuleVersion(): string
    {
        // TODO: fetch dynamically from composer.json (most probably)
        return '1.0.0';
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
