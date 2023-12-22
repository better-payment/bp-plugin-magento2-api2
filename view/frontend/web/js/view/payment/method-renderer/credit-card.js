define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'BetterPayment_Core/payment/credit-card'
            },
            // add required logic here
        });
    }
);
