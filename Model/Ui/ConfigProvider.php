<?php

namespace BetterPayment\Core\Model\Ui;

use BetterPayment\Core\Util\ConfigReader;
use Magento\Checkout\Model\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    private ConfigReader $configReader;

    public function __construct(ConfigReader $configReader)
    {
        $this->configReader = $configReader;
    }

    /**
     * @inheritDoc
     */
    public function getConfig()
    {
        return [
            'payment' => [
                $this->configReader->getConfigId(ConfigReader::SEPA_DIRECT_DEBIT_CREDITOR_ID) => $this->configReader->get(ConfigReader::SEPA_DIRECT_DEBIT_CREDITOR_ID),
                $this->configReader->getConfigId(ConfigReader::SEPA_DIRECT_DEBIT_COMPANY_NAME) => $this->configReader->get(ConfigReader::SEPA_DIRECT_DEBIT_COMPANY_NAME),
                $this->configReader->getConfigId(ConfigReader::SEPA_DIRECT_DEBIT_RISK_CHECK_AGREEMENT) => $this->configReader->get(ConfigReader::SEPA_DIRECT_DEBIT_RISK_CHECK_AGREEMENT),
                $this->configReader->getConfigId(ConfigReader::SEPA_DIRECT_DEBIT_B2B_CREDITOR_ID) => $this->configReader->get(ConfigReader::SEPA_DIRECT_DEBIT_B2B_CREDITOR_ID),
                $this->configReader->getConfigId(ConfigReader::SEPA_DIRECT_DEBIT_B2B_COMPANY_NAME) => $this->configReader->get(ConfigReader::SEPA_DIRECT_DEBIT_B2B_COMPANY_NAME),
                $this->configReader->getConfigId(ConfigReader::SEPA_DIRECT_DEBIT_B2B_RISK_CHECK_AGREEMENT) => $this->configReader->get(ConfigReader::SEPA_DIRECT_DEBIT_B2B_RISK_CHECK_AGREEMENT),
                $this->configReader->getConfigId(ConfigReader::INVOICE_RISK_CHECK_AGREEMENT) => $this->configReader->get(ConfigReader::INVOICE_RISK_CHECK_AGREEMENT),
                $this->configReader->getConfigId(ConfigReader::INVOICE_B2B_RISK_CHECK_AGREEMENT) => $this->configReader->get(ConfigReader::INVOICE_B2B_RISK_CHECK_AGREEMENT),
            ]
        ];
    }
}
