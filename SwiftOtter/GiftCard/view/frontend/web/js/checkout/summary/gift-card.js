
define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote'
], function (Component, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'SwiftOtter_GiftCard/checkout/summary/gift-card'
        },

        /**
         * @return {*}
         */
        isDisplayed: function () {
            return true;
        },

        /**
         * Get pure value.
         */
        getPureValue: function () {
            var totals = quote.getTotals()();

            return totals.total_segments.reduce(function(result, total){
                if(total.code === "gift_card") {
                    return total.value;
                }
                return result;
            }, 0);
        },

        /**
         * @return {*|String}
         */
        getValue: function () {
            return this.getFormattedPrice(this.getPureValue());
        }
    });
});
