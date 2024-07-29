/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

define([
    'Magento_Ui/js/form/element/ui-select',
    'underscore'
], function (Select, _) {
    'use strict';

    return Select.extend({
        initialize: function () {
            this._super();
            var elementValue = this.value();

            elementValue = _.isString(elementValue) ? elementValue.split(',') : elementValue;
            this.value(elementValue);

            return this;
        }
    });
});
