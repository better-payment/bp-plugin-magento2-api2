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
                template: 'BetterPayment_Core/payment/default',
                betterpayment_account_holder: customer.customerData.firstname + ' ' + customer.customerData.lastname,
                betterpayment_sepa_mandate: crypto.randomUUID(),
                creditor_id: window.checkoutConfig.payment.sepaDirectDebitCreditorID,
                company_name: window.checkoutConfig.payment.sepaDirectDebitCompanyName,
                risk_check_agreement: window.checkoutConfig.payment.sepaDirectDebitRiskCheckAgreement,
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

            validate: function () {
                let form = '#' + this.getCode() + '-fields-form';

                return $(form).validation() && $(form).validation('isValid');
            },
        });
    }
);
