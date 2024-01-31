<?php

namespace BetterPayment\Core\Gateway\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class BetterPaymentClient implements ClientInterface
{
    private Client $client;

    public function __construct(
        Client $client,
    ) {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $request = new Request(
            $transferObject->getMethod(),
            $transferObject->getUri(),
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
