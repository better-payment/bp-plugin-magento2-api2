<?php

namespace BetterPayment\Core\Controller\SuccessUrl;

use BetterPayment\Core\Gateway\Http\Client\Transaction;
use BetterPayment\Core\Gateway\Http\TransferFactory;
use BetterPayment\Core\Util\EntitySearcher;
use BetterPayment\Core\Util\PaymentStatusMapper;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class Index implements HttpGetActionInterface
{

    private Http $request;
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;
    private OrderRepositoryInterface $orderRepository;
    private InvoiceRepositoryInterface $invoiceRepository;
    private EntitySearcher $entitySearcher;
    private TransferFactory $transferFactory;
    private Transaction $transactionClient;

    public function __construct(
        Http $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        OrderRepositoryInterface $orderRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        EntitySearcher $entitySearcher,
        TransferFactory $transferFactory,
        Transaction $transactionClient,
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->orderRepository = $orderRepository;
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

            if ($response['status'] == PaymentStatusMapper::COMPLETED) {
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
        } catch (\Exception $exception) {
            $this->messageManager->addWarningMessage($exception->getMessage());
        }

        return $this->redirectFactory->create()->setPath('checkout/onepage/success');
    }
}
