<?php

namespace BetterPayment\Core\Api;

interface WebhookInterface
{
    /**
     * @return void
     */
    public function handle(): void;
}
