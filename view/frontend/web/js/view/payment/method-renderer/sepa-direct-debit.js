define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Customer/js/model/customer',
        'jquery',
    ],
    function (Component, customer, $) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'BetterPayment_Core/payment/sepa-direct-debit',
                betterpayment_account_holder: '',
                betterpayment_sepa_mandate: ''
            },

            initialize: function () {
                this._super();

                this.betterpayment_account_holder = customer.customerData.firstname + ' ' + customer.customerData.lastname;
                this.betterpayment_sepa_mandate = this.generateUUID();
            },

            getData: function () {
                return {
                    'method': this.getCode(),
                    'additional_data': {
                        'betterpayment_iban': $('#betterpayment_iban').val(),
                        'betterpayment_bic': $('#betterpayment_bic').val(),
                        'betterpayment_account_holder': this.betterpayment_account_holder,
                        'betterpayment_sepa_mandate': this.betterpayment_sepa_mandate,
                    }
                };
            },

            generateUUID: function () {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random() * 16 | 0,
                        v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }
        });
    }
);
