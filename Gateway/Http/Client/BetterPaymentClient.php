<?php

namespace BetterPayment\Core\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class BetterPaymentClient implements ClientInterface
{

    /**
     * @inheritDoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        return [
            'result' => 'success'
        ];
    }
}
