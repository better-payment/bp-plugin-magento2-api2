<?php

namespace BetterPayment\Core\Model\Ui;

use BetterPayment\Core\Util\ConfigReader;
use BetterPayment\Core\Util\PaymentMethod;
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
            ]
        ];
    }
}
