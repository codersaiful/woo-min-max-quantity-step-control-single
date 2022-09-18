(function($) {
    'use strict';
    
    $(document).ready(function () {
        /**
         * this will only output 2 digit 
         * Especially solved for OceanWP theme
         */
        jQuery(document).ready(function($){
            function CheckDecimal(inputtxt) { 
                if(!/^[-+]?[0-9]+\.[0-9]+$/.test(inputtxt)) { 
                    return true;
                } else { 
                    return false;
                }
            }
            var qty_box, qty_value, formatted_value;
            qty_box = $('.single-product input.input-text, .woocommerce-cart .product-quantity input.input-text');
            qty_box.on('change', function(){
                qty_value = $(this).val();
                if(!CheckDecimal(qty_value)){
                    formatted_value = parseFloat(qty_value).toFixed(2);
                    $(this).val(formatted_value);
                }else{
                    formatted_value = parseFloat(qty_value).toFixed(0);
                    $(this).val(formatted_value);
                }
            });	
        });
        
    });


})(jQuery);
