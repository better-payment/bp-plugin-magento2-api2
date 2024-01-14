<?php

namespace BetterPayment\Core\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Psr\Log\LoggerInterface;

class PaymentMethodDataAssignObserver extends AbstractDataAssignObserver
{
    private LoggerInterface $logger;

//    public function __construct(LoggerInterface $logger)
//    {
//        $this->logger = $logger;
//    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
//        $data = $this->readDataArgument($observer);
//        $paymentInfo = $this->readPaymentModelArgument($observer);
    }
}
