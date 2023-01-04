(function($) {
    'use strict';
    
    $(document).ready(function () {
        var decimal_separator = WCMMQ_DATA.decimal_separator;
        var decimal_count = WCMMQ_DATA.decimal_count;
        
        if(typeof decimal_count !== 'undefined'){
            alert
            decimal_count = parseInt(decimal_count);
        }else{
            decimal_count = 2;
        }
        
        if( decimal_separator === ',' ){
            $('input.input-text.qty.text').each(function(){
                $(this).addClass('wcmmq-main-input-box');
                var input_val = $(this).val();
                var val_with_coma = input_val.replace(/\./g, ',');
                var parentQuantity = $(this).parents('.quantity');
                parentQuantity.addClass('wcmmq-coma-separator-activated');
                parentQuantity.append('<input type="text" value="' + val_with_coma + '" class="wcmmq-second-input-box" id="wcmmq-second-input-id">');
            });

            $(document.body).on('keyup','.wcmmq-second-input-box',function(){
                var parentQuantity = $(this).parents('.quantity');
                var secondInputVal = $(this).val();
                var secondValWithDot = secondInputVal.replace(/,/g, '.');
                parentQuantity.find('.wcmmq-main-input-box').val(secondValWithDot);
            });
        }

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
            // this may not work. we need to check the classs 
            qty_box = $('.qib-button-wrapper .quantity input.input-text.qty.text, .single-product div.product form.cart .quantity input[type=number], .single-product div.product form.cart .quantity input[type=number]');
            qty_box.on('change', function(){
                qty_value = $(this).val();
                if(!CheckDecimal(qty_value)){
                    formatted_value = parseFloat(qty_value).toFixed(decimal_count);
                    formatted_value = formatted_value.replace(/0+$/,"");
                    $(this).val(formatted_value);
                }else{
                    formatted_value = parseFloat(qty_value).toFixed(0);
                    $(this).val(formatted_value);
                }

                if( decimal_separator === ',' ){
                    var val_with_coma = qty_value.replace(/\./g, ',');
                    $(this).parents('.quantity').find('.wcmmq-second-input-box').val(val_with_coma);

                }
            });	
        });
        
    });


})(jQuery);
