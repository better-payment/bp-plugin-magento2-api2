<?php

namespace BetterPayment\Core\Util;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;

class EntitySearcher
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private OrderRepositoryInterface $orderRepository;
    private TransactionRepositoryInterface $transactionRepository;
    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        TransactionRepositoryInterface $transactionRepository,
        InvoiceRepositoryInterface $invoiceRepository,
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function getOrderByTransactionId(string $transactionId): OrderInterface
    {
        $transaction = $this->getTransactionByTxnId($transactionId);

        return $this->orderRepository->get($transaction->getOrderId());
    }

    public function getTransactionByTxnId(string $txnId): TransactionInterface
    {
        $this->searchCriteriaBuilder->addFilter('txn_id', $txnId);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        /* @var TransactionInterface $transaction */
        $transaction = $this->transactionRepository->getList($searchCriteria)->getFirstItem();

        return $transaction;
    }

    public function getInvoiceByTransactionId(string $transactionId): InvoiceInterface
    {
        $this->searchCriteriaBuilder->addFilter('transaction_id', $transactionId);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        /** @var InvoiceInterface $invoice */
        $invoice = $this->invoiceRepository->getList($searchCriteria)->getFirstItem();

        return $invoice;
    }
}
