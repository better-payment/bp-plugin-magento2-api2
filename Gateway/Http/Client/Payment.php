<?php

namespace BetterPayment\Core\Gateway\Http\Client;

use BetterPayment\Core\Util\ConfigReader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class Payment implements ClientInterface
{
    private Client $client;
    private ConfigReader $configReader;

    public function __construct(
        Client $client,
        ConfigReader $configReader
    ) {
        $this->client = $client;
        $this->configReader = $configReader;
    }

    /**
     * @inheritDoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $request = new Request(
            'POST',
            $this->configReader->getApiUrl() . '/rest/payment',
            $transferObject->getHeaders(),
            $transferObject->getBody()
        );

        try {
            $response = $this->client->send($request);
            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody['error_code'] == 0) {
                return $responseBody;
            }
            else {
                throw new ClientException(__('Better Payment Client ERROR: ' . $response->getBody()));
            }
        } catch (GuzzleException $exception) {
            throw new ClientException(__('Better Payment Client ERROR: ' . $exception->getMessage()));
        }
    }
}
