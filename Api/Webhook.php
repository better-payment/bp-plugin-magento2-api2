<?php

namespace BetterPayment\Core\Api;

use BetterPayment\Core\Util\ConfigReader;
use BetterPayment\Core\Util\EntitySearcher;
use BetterPayment\Core\Util\PaymentStatusMapper;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Webapi\Rest\Request;
use Symfony\Component\HttpFoundation\Response;

class Webhook implements WebhookInterface
{
    private Request $request;
    private OrderRepositoryInterface $orderRepository;
    private InvoiceRepositoryInterface $invoiceRepository;
    private ConfigReader $configReader;
    private PaymentStatusMapper $paymentStatusMapper;
    private EntitySearcher $entitySearcher;

    public function __construct(
        Request $request,
        OrderRepositoryInterface $orderRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        ConfigReader $configReader,
        PaymentStatusMapper $paymentStatusMapper,
        EntitySearcher $entitySearcher,
    ) {
        $this->request = $request;
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->configReader = $configReader;
        $this->paymentStatusMapper = $paymentStatusMapper;
        $this->entitySearcher = $entitySearcher;
    }

    /**
     * @inheritdoc
     */
    public function handle(): void
    {
        try {
            $params = $this->request->getBodyParams();

            // Calculate checksum without checksum parameter itself and sign it with INCOMING_KEY
            unset($params['checksum']);
            $query = http_build_query($params, '', '&');
            $checksum = sha1($query . $this->configReader->getIncomingKey());

            if ($checksum == $this->request->getParam('checksum')) {
                $transactionId = $params['transaction_id'];
                $transactionStatus = $params['status'];

                $invoice = $this->entitySearcher->getInvoiceByTransactionId($transactionId);
                // Capture the invoice if transaction status is completed
                if ($transactionStatus == PaymentStatusMapper::COMPLETED) {
                    $invoice->capture();
                    $this->invoiceRepository->save($invoice);
                }

                // Update the order status/state
                $order = $invoice->getOrder();
                $status = $this->paymentStatusMapper->mapFromPaymentGatewayStatus($transactionStatus);
                $order->setStatus($status);
                $order->setState($status);
                $order->addCommentToStatusHistory(__('Order status updated via webhook'));
                $this->orderRepository->save($order);

                $response = new Response('Status updated successfully');
            }
            else {
                $response = new Response('Checksum verification failed', 401);
            }

            $response->send();
        }
        catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 500);
            $response->send();
        }
    }
}
