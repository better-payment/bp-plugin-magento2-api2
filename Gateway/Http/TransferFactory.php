<?php

namespace BetterPayment\Core\Gateway\Http;

use BetterPayment\Core\Util\ConfigReader;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;

class TransferFactory implements TransferFactoryInterface
{
    private TransferBuilder $transferBuilder;
    private ConfigReader $configReader;

    public function __construct(TransferBuilder $transferBuilder, ConfigReader $configReader)
    {
        $this->transferBuilder = $transferBuilder;
        $this->configReader = $configReader;
    }

    /**
     * @inheritDoc
     */
    public function create(array $request)
    {
        return $this->transferBuilder
            ->setMethod('POST')
            ->setHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->configReader->getApiKey() . ':' . $this->configReader->getOutgoingKey()),
            ])
            ->setBody(json_encode($request))
//            ->setAuthUsername($this->configReader->getApiKey())
//            ->setAuthPassword($this->configReader->getOutgoingKey())
            ->setUri($this->configReader->getApiUrl() . '/rest/payment')
            ->build();
    }
}
