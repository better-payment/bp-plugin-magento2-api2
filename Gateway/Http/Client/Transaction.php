<?php

namespace BetterPayment\Core\Gateway\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class Transaction implements ClientInterface
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
        $transactionId = json_decode($transferObject->getBody(), true)['transaction_id'];
        $request = new Request(
            'GET',
            $transferObject->getUri() . '/rest/transactions/' . $transactionId,
            $transferObject->getHeaders()
        );

        try {
            $response = $this->client->send($request);
            $responseBody = json_decode($response->getBody(), true);

            if (isset($responseBody['error_code'])) {
                throw new ClientException(__('Payment Gateway Error: ' . $responseBody['error_message']));
            }

            return $responseBody;
        } catch (GuzzleException $exception) {
            throw new ClientException(__('Payment Gateway Error: ' . $exception->getMessage()));
        }
    }
}
