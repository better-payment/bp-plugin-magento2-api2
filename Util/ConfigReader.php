<?php

namespace BetterPayment\Core\Util;

use BetterPayment\Core\Model\Config\Source\Whitelabel;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigReader
{
    public const STORE_NAME = 'general/store_information/name';

    public const ENVIRONMENT = 'betterpayment/base_configuration/environment';
    public const WHITELABEL = 'betterpayment/base_configuration/whitelabel';
    public const TEST_API_KEY = 'betterpayment/test_environment_credentials/testApiKey';
    public const TEST_OUTGOING_KEY = 'betterpayment/test_environment_credentials/testOutgoingKey';
    public const TEST_INCOMING_KEY = 'betterpayment/test_environment_credentials/testIncomingKey';
    public const PRODUCTION_API_KEY = 'betterpayment/production_environment_credentials/productionApiKey';
    public const PRODUCTION_OUTGOING_KEY = 'betterpayment/production_environment_credentials/productionOutgoingKey';
    public const PRODUCTION_INCOMING_KEY = 'betterpayment/production_environment_credentials/productionIncomingKey';

    private ScopeConfigInterface $scopeConfig;
    private Whitelabel $whitelabel;

    public function __construct(ScopeConfigInterface $scopeConfig, Whitelabel $whitelabel)
    {
        $this->scopeConfig = $scopeConfig;
        $this->whitelabel = $whitelabel;
    }

    public function get(string $path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    public function getWebhookUrl() {
        // TODO: fetch dynamically
        return 'https://localhost/rest/V1/betterpayment/webhook';
    }

    public function getModuleVersion() {
        // TODO: fetch dynamically from composer.json (most probably)
        return '1.0.0';
    }

    public function getApiUrl() {
        $whitelabel = $this->get(self::WHITELABEL);
        $environment = $this->get(self::ENVIRONMENT);

        $whitelabelOptions = $this->whitelabel->toOptionArray();

        foreach ($whitelabelOptions as $whitelabelOption) {
            if ($whitelabelOption['value'] == $whitelabel) {
                return $whitelabelOption[$environment]['api_url'];
            }
        }

        // TODO: return fallback url such as null or empty string
        return null;
    }

    public function getApiKey()
    {
        return $this->get(self::ENVIRONMENT) == 'test'
            ? $this->get(self::TEST_API_KEY)
            : $this->get(self::PRODUCTION_API_KEY);
    }

    public function getOutgoingKey()
    {
        return $this->get(self::ENVIRONMENT) == 'test'
            ? $this->get(self::TEST_OUTGOING_KEY)
            : $this->get(self::PRODUCTION_OUTGOING_KEY);
    }

    public function getIncomingKey()
    {
        return $this->get(self::ENVIRONMENT) == 'test'
            ? $this->get(self::TEST_INCOMING_KEY)
            : $this->get(self::PRODUCTION_INCOMING_KEY);
    }

}
