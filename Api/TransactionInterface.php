<?php

namespace BetterPayment\Core\Api;

interface TransactionInterface
{
    /**
     * @return array
     */
    public function getRedirectUrl();
}
