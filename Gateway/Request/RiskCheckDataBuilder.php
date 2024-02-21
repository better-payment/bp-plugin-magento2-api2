<?php

namespace BetterPayment\Core\Gateway\Request;

use BetterPayment\Core\Util\ConfigReader;
use BetterPayment\Core\Util\PaymentMethod;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class RiskCheckDataBuilder implements BuilderInterface
{
    private ConfigReader $configReader;
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(ConfigReader $configReader, CustomerRepositoryInterface $customerRepository)
    {
        $this->configReader = $configReader;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        /** @var PaymentDataObjectInterface $paymentData */
        $paymentData = $buildSubject['payment'];

        /** @var OrderPaymentInterface $payment */
        $payment = $paymentData->getPayment();
        $order = $paymentData->getOrder();

        $data = [];

        $paymentMethod = $payment->getMethod();

        if (($paymentMethod == PaymentMethod::SEPA_DIRECT_DEBIT && $this->configReader->get(ConfigReader::SEPA_DIRECT_DEBIT_COLLECT_DATE_OF_BIRTH)) ||
            ($paymentMethod == PaymentMethod::INVOICE && $this->configReader->get(ConfigReader::INVOICE_COLLECT_DATE_OF_BIRTH))) {
            $data['date_of_birth'] = $this->getDateOfBirth($order->getCustomerId());
        }

        if (($paymentMethod == PaymentMethod::SEPA_DIRECT_DEBIT && $this->configReader->get(ConfigReader::SEPA_DIRECT_DEBIT_COLLECT_GENDER)) ||
            ($paymentMethod == PaymentMethod::INVOICE && $this->configReader->get(ConfigReader::INVOICE_COLLECT_GENDER))) {
            $data['gender'] = $this->getGender($order->getCustomerId());
        }

        return $data;
    }

    private function getDateOfBirth(string $customerId): ?string
    {
        return $this->customerRepository->getById($customerId)->getDob();
    }

    private function getGender(string $customerId): ?string
    {
        return match ($this->customerRepository->getById($customerId)->getGender()) {
            '1' => 'm',
            '2' => 'f',
            '3' => 'd',
            default => null
        };
    }
}
