<?php

namespace BetterPayment\Core\Api;

use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;

class Transaction implements TransactionInterface
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }


    /**
     * @return array
     * @throws LocalizedException
     */
    public function getRedirectUrl()
    {
        $order = $this->session->getLastRealOrder();

        if (!$order->getId()) {
            throw new LocalizedException(__('Order does not exist!'));
        }

        return $order->getPayment()->getAdditionalInformation('redirect_url');
    }
}
