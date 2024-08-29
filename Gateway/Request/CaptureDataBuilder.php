<?php

namespace BetterPayment\Core\Gateway\Request;

use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Framework\App\Request\Http;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Model\Order;

class CaptureDataBuilder implements BuilderInterface
{
    private Http $request;
    private InvoiceRepositoryInterface $invoiceRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        Http $request,
        InvoiceRepositoryInterface $invoiceRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
    ) {
        $this->request = $request;
        $this->invoiceRepository = $invoiceRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        /** @var PaymentDataObjectInterface $paymentData */
        $paymentData = $buildSubject['payment'];

        /** @var Order $payment */
        $payment = $paymentData->getPayment();

        if ($this->request->has('invoice_id')) {
            $invoiceId = $this->invoiceRepository->get($this->request->getParam('invoice_id'))->getIncrementId();
        }
        else {
            $lastId = $this->invoiceRepository->getList($this->searchCriteriaBuilder->create())->getLastItem()->getIncrementId();
            $numberOfDigits = strlen($lastId);
            $lastIdInt = (int) $lastId;
            $nextIdInt = $lastIdInt + 1;
            $invoiceId = str_pad($nextIdInt, $numberOfDigits, '0', STR_PAD_LEFT);
        }

        return [
            'transaction_id' => $payment->getLastTransId(),
            'amount' => $buildSubject['amount'],
            'invoice_id' => $invoiceId,
        ];
    }
}
