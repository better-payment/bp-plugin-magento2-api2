<?php

namespace BetterPayment\Core\Controller\ErrorUrl;

use BetterPayment\Core\Gateway\Http\Client\Transaction;
use BetterPayment\Core\Gateway\Http\TransferFactory;
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
    private Session $checkoutSession;
    private OrderRepositoryInterface $orderRepository;
    private TransactionRepositoryInterface $transactionRepository;
    private InvoiceRepositoryInterface $invoiceRepository;
    private EntitySearcher $entitySearcher;
    private TransferFactory $transferFactory;
    private Transaction $transactionClient;

    public function __construct(
        Http $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        Session $checkoutSession,
        OrderRepositoryInterface $orderRepository,
        TransactionRepositoryInterface $transactionRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        EntitySearcher $entitySearcher,
        TransferFactory $transferFactory,
        Transaction $transactionClient
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->checkoutSession = $checkoutSession;
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->entitySearcher = $entitySearcher;
        $this->transferFactory = $transferFactory;
        $this->transactionClient = $transactionClient;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $transactionId = $this->request->getParam('transaction_id');

            $response = $this->transactionClient->placeRequest($this->transferFactory->create([
                'transaction_id' => $transactionId
            ]));

            if ($response['status'] == PaymentStatusMapper::CANCELED) {
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
                $order->addCommentToStatusHistory(__('Payment failed on Payment Gateway.'));
                $this->orderRepository->save($order);

                $this->messageManager->addErrorMessage(__('Payment failed on Payment Gateway. Try to reorder again.'));
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        $this->checkoutSession->restoreQuote();
        return $this->redirectFactory->create()->setPath('checkout/cart');
    }
}
