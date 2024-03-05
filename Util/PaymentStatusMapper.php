<?php

namespace BetterPayment\Core\Util;

use Magento\Sales\Model\Order;

class PaymentStatusMapper
{
    private const STARTED = 'started';
    private const PENDING = 'pending';
    private const COMPLETED = 'completed';
    private const ERROR = 'error';
    private const CANCELED = 'canceled'; // not mentioned in paper
    private const DECLINED = 'declined';
    private const REFUNDED = 'refunded';
    private const CHARGEBACK = 'chargeback';

    public function mapFromPaymentGatewayStatus(string $status): string
    {
        return match ($status) {
            self::STARTED, self::PENDING => Order::STATE_PENDING_PAYMENT,
            self::COMPLETED => Order::STATE_PROCESSING,
            self::ERROR, self::DECLINED, self::CANCELED => Order::STATE_CANCELED,
            self::REFUNDED, self::CHARGEBACK => Order::STATE_CLOSED,
            default => Order::STATE_PAYMENT_REVIEW,
        };
    }
}
