<?php

namespace BetterPayment\Core\Controller\SuccessUrl;

use BetterPayment\Core\Util\PaymentStatusMapper;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\Order;

class Index implements HttpGetActionInterface
{
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;
    private Session $session;
    private Http $request;
    private PaymentStatusMapper $paymentStatusMapper;

    public function __construct(
        Http $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        Session $session,
        PaymentStatusMapper $paymentStatusMapper
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->session = $session;
        $this->paymentStatusMapper = $paymentStatusMapper;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        // TODO: fetch transaction status dynamically using API request to endpoint GET rest/transactions/TRANSACTION_ID
        $transactionId = $this->request->getParam('transaction_id');
        $status = 'completed';

        $order = $this->session->getLastRealOrder();
        $order->setStatus($this->paymentStatusMapper->mapFromPaymentGatewayStatus($status))
            ->setState($this->paymentStatusMapper->mapFromPaymentGatewayStatus($status));
        // TODO: add something to history as well
        $order->save(); // TODO: change this to Save via Repository code

        $this->messageManager->addSuccessMessage(__('Payment successfully completed'));
        return $this->redirectFactory->create()->setPath('checkout/onepage/success');
    }
}
