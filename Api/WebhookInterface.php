<?php

namespace BetterPayment\Core\Api;

use Magento\Sales\Api\Data\OrderInterface;

interface WebhookInterface
{
    /**
     * @return OrderInterface
     */
    public function handle();
}
