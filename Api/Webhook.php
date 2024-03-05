<?php

namespace BetterPayment\Core\Api;

class Webhook implements WebhookInterface
{
    /**
     * @return string
     */
    public function test()
    {
        // TODO: fetch request body
        // https://www.webspeaks.in/2020/06/magento-2-get-post-body-content-in-rest-api.html
        return '4567890';
    }
}
