<?php

namespace BetterPayment\Core\Controller\SuccessUrl;

use BetterPayment\Core\Util\EntitySearcher;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class Index implements HttpGetActionInterface
{

    private Http $request;
    private RedirectFactory $redirectFactory;
    private OrderRepositoryInterface $orderRepository;
    private InvoiceRepositoryInterface $invoiceRepository;
    private EntitySearcher $entitySearcher;

    public function __construct(
        Http $request,
        RedirectFactory $redirectFactory,
        OrderRepositoryInterface $orderRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        EntitySearcher $entitySearcher,
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->entitySearcher = $entitySearcher;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $transactionId = $this->request->getParam('transaction_id');

        // TODO: fetch transaction status dynamically using API request to endpoint GET rest/transactions/TRANSACTION_ID
        $status = 'completed';

        if ($status == 'completed') {
            // Capture the invoice
            $invoice = $this->entitySearcher->getInvoiceByTransactionId($transactionId);
            $invoice->capture();
            $this->invoiceRepository->save($invoice);

            // Update the order status/state
            $order = $invoice->getOrder();
            $order->setStatus(Order::STATE_PROCESSING);
            $order->setState($order->getConfig()->getStateDefaultStatus(Order::STATE_PROCESSING));
            $this->orderRepository->save($order);
        }

        return $this->redirectFactory->create()->setPath('checkout/onepage/success');
    }
}
