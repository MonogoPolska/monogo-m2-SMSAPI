define([
    'jquery',
    'Magento_Ui/js/lib/validation/validator',
    'Magento_Ui/js/form/element/abstract'
], function ($, validator, Element) {
    'use strict';

    return Element.extend({
        initialize: function () {
            this._super();
        },

        checked: function () {
            return true;
        },


    });
});