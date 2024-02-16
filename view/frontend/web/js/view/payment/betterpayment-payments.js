define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'betterpayment_credit_card',
                component: 'BetterPayment_Core/js/view/payment/method-renderer/async-payment-method'
            },
            {
                type: 'betterpayment_giropay',
                component: 'BetterPayment_Core/js/view/payment/method-renderer/async-payment-method'
            },
            {
                type: 'betterpayment_paydirekt',
                component: 'BetterPayment_Core/js/view/payment/method-renderer/async-payment-method'
            },
            {
                type: 'betterpayment_sofort',
                component: 'BetterPayment_Core/js/view/payment/method-renderer/async-payment-method'
            },
            {
                type: 'betterpayment_request_to_pay',
                component: 'BetterPayment_Core/js/view/payment/method-renderer/async-payment-method'
            },
            {
                type: 'betterpayment_paypal',
                component: 'BetterPayment_Core/js/view/payment/method-renderer/async-payment-method'
            },
            {
                type: 'betterpayment_sepa_direct_debit',
                component: 'BetterPayment_Core/js/view/payment/method-renderer/sepa-direct-debit'
            },
            // other payment method renderers if required
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
