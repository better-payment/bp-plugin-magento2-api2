<?php

namespace BetterPayment\Core\Controller\ErrorUrl;

use BetterPayment\Core\Util\EntitySearcher;
use BetterPayment\Core\Util\PaymentStatusMapper;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Sales\Model\Order;

class Index implements HttpGetActionInterface
{
    private Http $request;
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;
    private Session $session;
    private OrderRepositoryInterface $orderRepository;
    private TransactionRepositoryInterface $transactionRepository;
    private InvoiceRepositoryInterface $invoiceRepository;
    private PaymentStatusMapper $paymentStatusMapper;
    private EntitySearcher $entitySearcher;

    public function __construct(
        Http $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        Session $session,
        OrderRepositoryInterface $orderRepository,
        TransactionRepositoryInterface $transactionRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        PaymentStatusMapper $paymentStatusMapper,
        EntitySearcher $entitySearcher,
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->session = $session;
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->paymentStatusMapper = $paymentStatusMapper;
        $this->entitySearcher = $entitySearcher;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $transactionId = $this->request->getParam('transaction_id');

        // TODO: fetch transaction status dynamically using API request to endpoint GET rest/transactions/TRANSACTION_ID
        $status = 'canceled';

        if ($status == 'canceled') {
            // Cancel the invoice
            $invoice = $this->entitySearcher->getInvoiceByTransactionId($transactionId);
            $invoice->cancel();
            $this->invoiceRepository->save($invoice);

            // Close the transaction
            $transaction = $this->entitySearcher->getTransactionByTxnId($transactionId);
            $transaction->setIsClosed(true);
            $this->transactionRepository->save($transaction);

            // Update the order status/state
            $order = $invoice->getOrder();
            $order->setStatus(Order::STATE_CANCELED);
            $order->setState($order->getConfig()->getStateDefaultStatus(Order::STATE_CANCELED));
            $order->addCommentToStatusHistory('Payment failed on Payment Gateway.', $status);
            $this->orderRepository->save($order);

            $this->messageManager->addErrorMessage(__('Payment failed on Payment Gateway. Try to reorder again.'));
        }

        return $this->redirectFactory->create()->setPath('sales/order/history');
    }
}
