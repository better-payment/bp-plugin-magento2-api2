<?php

namespace BetterPayment\Core\Api;

use BetterPayment\Core\Util\ConfigReader;
use BetterPayment\Core\Util\EntitySearcher;
use BetterPayment\Core\Util\PaymentStatusMapper;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Webapi\Rest\Request;
use Symfony\Component\HttpFoundation\Response;

class Webhook implements WebhookInterface
{
    private Request $request;
    private OrderRepositoryInterface $orderRepository;
    private ConfigReader $configReader;
    private PaymentStatusMapper $paymentStatusMapper;
    private EntitySearcher $entitySearcher;

    public function __construct(
        Request $request,
        OrderRepositoryInterface $orderRepository,
        ConfigReader $configReader,
        PaymentStatusMapper $paymentStatusMapper,
        EntitySearcher $entitySearcher,
    ) {
        $this->request = $request;
        $this->orderRepository = $orderRepository;
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
                // Update the order status/state
                $transactionId = $params['transaction_id'];
                $status = $params['status'];

                $order = $this->entitySearcher->getOrderByTransactionId($transactionId);
                $order->setStatus($this->paymentStatusMapper->mapFromPaymentGatewayStatus($status));
                $order->setState($this->paymentStatusMapper->mapFromPaymentGatewayStatus($status));
                $order->addCommentToStatusHistory(__('Order status updated via webhook'), $status);
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
