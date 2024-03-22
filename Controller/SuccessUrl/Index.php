<?php

namespace BetterPayment\Core\Controller\SuccessUrl;

use BetterPayment\Core\Util\EntitySearcher;
use BetterPayment\Core\Util\PaymentStatusMapper;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;

class Index implements HttpGetActionInterface
{

    private Http $request;
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;
    private Session $session;
    private OrderRepositoryInterface $orderRepository;
    private InvoiceRepositoryInterface $invoiceRepository;
    private PaymentStatusMapper $paymentStatusMapper;
    private EntitySearcher $entitySearcher;

    public function __construct(
        Http $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        Session $session,
        OrderRepositoryInterface $orderRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        PaymentStatusMapper $paymentStatusMapper,
        EntitySearcher $entitySearcher,
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->session = $session;
        $this->orderRepository = $orderRepository;
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

            $this->messageManager->addSuccessMessage(__('Payment successfully completed'));
        }

        return $this->redirectFactory->create()->setPath('checkout/onepage/success');
    }
}
