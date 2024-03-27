define(
    [
        'Magento_Checkout/js/view/payment/default',
        'jquery',
    ],
    function (Component, $) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'BetterPayment_Core/payment/default',
                risk_check_agreement: window.checkoutConfig.payment.invoiceB2BRiskCheckAgreement,
            },

            validate: function () {
                let form = '#' + this.getCode() + '-fields-form';

                return $(form).validation() && $(form).validation('isValid');
            },
        });
    }
);
