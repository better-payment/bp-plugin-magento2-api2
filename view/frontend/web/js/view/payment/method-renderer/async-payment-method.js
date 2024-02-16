define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/full-screen-loader',
        'mage/storage',
        'Magento_Checkout/js/model/url-builder',
    ],
    function (Component, quote, customer, fullScreenLoader, storage, urlBuilder) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'BetterPayment_Core/payment/default',
                redirectAfterPlaceOrder: false,
            },

            afterPlaceOrder: function () {
                fullScreenLoader.startLoader();

                let serviceUrl = urlBuilder.createUrl('/betterpayment/transaction', {});
                storage.get(
                    serviceUrl, false
                ).done(function(response){
                    window.location.replace(response)
                });
            }
        });
    }
);
