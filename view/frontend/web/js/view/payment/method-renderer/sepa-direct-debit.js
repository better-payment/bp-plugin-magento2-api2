define(
    [
        'Magento_Checkout/js/view/payment/default',
        'jquery'
    ],
    function (Component, $) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'BetterPayment_Core/payment/sepa-direct-debit'
            },

            getData: function () {
                return {
                    'method': this.getCode(),
                    'additional_data': {
                        'betterpayment_account_holder': $('#betterpayment_account_holder').val(),
                        'betterpayment_iban': $('#betterpayment_iban').val(),
                        'betterpayment_bic': $('#betterpayment_bic').val(),
                        'betterpayment_sepa_mandate': $('#betterpayment_sepa_mandate').val(),
                    }
                };
            },
        });
    }
);
