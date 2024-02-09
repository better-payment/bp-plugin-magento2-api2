<?php

namespace BetterPayment\Core\Gateway\Request;

use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class RedirectUrlDataBuilder implements BuilderInterface
{
    private UrlInterface $url;

    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        return [
            'success_url' => $this->url->getUrl('checkout/onepage/success'),
            'error_url' => $this->url->getUrl('redirect/errorurl'),
        ];
    }
}
