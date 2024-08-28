<?php

namespace BetterPayment\Core\Gateway\Request;

use BetterPayment\Core\Util\EntitySearcher;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Framework\App\Request\Http;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Model\Order;

class CaptureDataBuilder implements BuilderInterface
{

    private Http $request;
    private InvoiceRepositoryInterface $invoiceRepository;
    private EntitySearcher $entitySearcher;

    public function __construct(Http $request, InvoicerepositoryInterface $invoiceRepository, EntitySearcher $entitySearcher)
    {
        $this->request = $request;
        $this->invoiceRepository = $invoiceRepository;
        $this->entitySearcher = $entitySearcher;
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
            $lastId = $this->entitySearcher->getInvoiceByTransactionId($payment->getLastTransId())->getIncrementId();
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
