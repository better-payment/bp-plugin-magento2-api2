<?php

namespace BetterPayment\Core\Controller\ErrorUrl;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\Order;

class Index implements HttpGetActionInterface
{
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;
    private Session $session;

    public function __construct(
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        Session $session
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $order = $this->session->getLastRealOrder();
        $order->setStatus(Order::STATE_CANCELED)->setState(Order::STATE_CANCELED);
        // TODO: add something to history as well
        $order->save(); // TODO: change this to Save via Repository code

        $this->messageManager->addErrorMessage(__('Payment failed in Payment Gateway page. Try to reorder again.'));
        return $this->redirectFactory->create()->setPath('sales/order/history');
    }
}
