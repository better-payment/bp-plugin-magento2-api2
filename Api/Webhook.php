<?php

namespace BetterPayment\Core\Api;

use BetterPayment\Core\Util\ConfigReader;
use BetterPayment\Core\Util\PaymentStatusMapper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class Webhook implements WebhookInterface
{
    private Request $request;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private OrderRepositoryInterface $orderRepository;
    private TransactionRepositoryInterface $transactionRepository;
    private ConfigReader $configReader;
    private PaymentStatusMapper $paymentStatusMapper;

    public function __construct(
        Request $request,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        TransactionRepositoryInterface $transactionRepository,
        ConfigReader $configReader,
        PaymentStatusMapper $paymentStatusMapper
    ) {
        $this->request = $request;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->configReader = $configReader;
        $this->paymentStatusMapper = $paymentStatusMapper;
    }

    /**
     * @inheritdoc
     */
    public function handle()
    {
        try {
            $params = $this->request->getBodyParams();

            unset($params['checksum']);
            $query = http_build_query($params, '', '&', PHP_QUERY_RFC1738);
            $checksum = sha1($query . $this->configReader->getIncomingKey());

            // Calculate checksum without checksum parameter itself, and sign it with INCOMING_KEY
            // NOTE: "content-type": "application/x-www-form-urlencoded" for this request
            // that's why $request->request is used to fetch parameters
            if ($checksum == $this->request->getParam('checksum')) {
                // update order state
                $transactionId = $params['transaction_id'];
                $status = $params['status'];

                $order = $this->getOrderByTransactionId($transactionId);
                $order->setStatus($this->paymentStatusMapper->mapFromPaymentGatewayStatus($status))
                    ->setState($this->paymentStatusMapper->mapFromPaymentGatewayStatus($status));
                // TODO: add something to history as well
                $order->save(); // TODO: change this to Save via Repository code

                $response = new Response('Status updated successfully');
                $response->send();
            }
            else {
                $response = new Response('Checksum verification failed', 401);
                $response->send();
            }
        }
        catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 500);
            $response->send();
        }
    }


    private function getOrderByTransactionId(string $transactionId): OrderInterface
    {
        $this->searchCriteriaBuilder->addFilter('txn_id', $transactionId);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        /* @var \Magento\Sales\Model\Order\Payment\Transaction $transaction */
        $transaction = $this->transactionRepository->getList($searchCriteria)->getFirstItem();

        return $this->orderRepository->get($transaction->getOrderId());
    }
}
