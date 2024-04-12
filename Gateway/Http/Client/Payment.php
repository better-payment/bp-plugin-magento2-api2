<?php

namespace BetterPayment\Core\Gateway\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class Payment implements ClientInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $request = new Request(
            'POST',
            $transferObject->getUri() . '/rest/payment',
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
                throw new ClientException(__('Payment Gateway Error: ' . $responseBody['error_message']));
            }
        } catch (GuzzleException $exception) {
            throw new ClientException(__('Payment Gateway Error: ' . $exception->getMessage()));
        }
    }
}
