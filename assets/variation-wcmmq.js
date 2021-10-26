(function($) {
    'use strict';
    
    $(document).ready(function () {
        QuantityChange();
        ourAttrChange();
        
    });

    // Make the code work after executing AJAX.
    $(document).ajaxComplete(function () {
        QuantityChange();
    });

    /**
     * When variation changed input value should be updated as per min, max attr
     * 
     * @since 1.9
     */
    function ourAttrChange(){

        if( WCMMQ_DATA.product_type != 'variable') return;

        $('div.quantity input[type=number]').attrchange({
            trackValues: true, /* Default to false, if set to true the event object is 
                        updated with old and new value.*/
            callback: function (event) { 
                // console.log(event);
                if(event.attributeName == 'min'){
                    // console.log(event.oldValue, event.newValue);
                    $($(event.target).val(event.newValue));
                }
            }        
        });
    }

    function QuantityChange() {
        $(document).off("click", ".qib-button").on("click", ".qib-button", function () {

            var qty = $(this).siblings(".quantity").find(".qty");
            // Read value and attributes min, max, step.
            var val = parseFloat(qty.val());
            var max = parseFloat(qty.attr("max"));
            var min = parseFloat(qty.attr("min"));
            var step = parseFloat(qty.attr("step"));

            if ($(this).is(".plus")) {
                if (val === max)
                    return false;
                if (isNaN(val)) {
                    qty.val(step);
                    return false;
                }
                if (val + step > max) {
                    qty.val(max);
                } else {
                    qty.val(val + step);
                }
            } else {
                if (val === min)
                    return false;
                if (isNaN(val)) {
                    qty.val(min);
                    return false;
                }
                if (val - step < min) {
                    qty.val(min);
                } else {
                    qty.val(val - step);
                }
            }

            qty.val(Math.round(qty.val() * 100) / 100);
            qty.trigger("change");
        });
    }
})(jQuery);
