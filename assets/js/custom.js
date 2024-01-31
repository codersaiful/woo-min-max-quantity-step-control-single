(function($) {
    'use strict';
    function addCustomInputBox(){
        var decimal_separator = WCMMQ_DATA.decimal_separator;
        if( decimal_separator != '.' ){
            $('input.input-text.qty.text').not('.wcmmq-second-input-box,.wcmmq-main-input-box').each(function(){
                
                $(this).addClass('wcmmq-main-input-box');
                var input_val = $(this).val();
                var val_with_coma = input_val.replace(/\./g, decimal_separator);
                var parentQuantity = $(this).parents('.quantity');
                parentQuantity.addClass('wcmmq-coma-separator-activated');
                $(this).after('<input type="text" value="' + val_with_coma + '" class="wcmmq-second-input-box input-text qty text" id="wcmmq-second-input-id">');
            });
        }
    }
    $(document).ajaxComplete(function () {
        setTimeout(addCustomInputBox,320);
    });
    $(document).ready(function () {
        var decimal_separator = WCMMQ_DATA.decimal_separator;
        var decimal_count = WCMMQ_DATA.decimal_count;
        
        if(typeof decimal_count !== 'undefined'){
            decimal_count = parseInt(decimal_count);
        }else{
            decimal_count = 2;
        }
        
        addCustomInputBox();

        
        $(document.body).on('wpt_changed_variations',function(e, targetAttributeObject){

            if(targetAttributeObject.status == false){
                return false;
            }
            var product_id = targetAttributeObject.product_id;
            var variation_id = targetAttributeObject.variation_id;
            var variation_data = $('#wcmmq_variation_data_' + product_id).data('variation_data');
            var qty_boxWPT = $('.product_id_' + product_id + ' input.input-text.qty.text');
            distributeMinMax(variation_id,variation_data,qty_boxWPT);
        });

        /**
         * Custom work
         */
        $('input.input-text.qty.text.wcmmq-qty-custom-validation').on('invalid', function() {

            var DataObject = $('.wcmmq-json-options-data');
            var json_data = DataObject.data('wcmmq_json_data');
            // console.log(json_data);
            var step_validation_msg = DataObject.data('step_error_valiation');
            var msg_min_limit = DataObject.data('msg_min_limit');
            var msg_max_limit = DataObject.data('msg_max_limit');

            var product_name = "🎁 Product";

            // var step_validation_msg = 'Please enter a valid value. The two nearest valid values are [should_min] and [should_next]';
            // Parse input value as a float
            var inputValue = parseFloat($(this).val());

            // Get the min, max, and step attributes
            var min = parseFloat($(this).attr('min'));
            var max = parseFloat($(this).attr('max'));
            var step = parseFloat($(this).attr('step'));

            // Calculate the nearest valid values
            var lowerNearest = Math.floor((inputValue - min) / step) * step + min;
            var upperNearest = lowerNearest + step;

            console.log(min,max,inputValue);
            if( inputValue <  min){
                step_validation_msg = msg_min_limit;
                step_validation_msg = step_validation_msg.replace("[min_quantity]", min);
            }else if(inputValue > max && max > min){
                step_validation_msg = msg_max_limit;
                step_validation_msg = step_validation_msg.replace("[max_quantity]", max);
            }else{
                step_validation_msg = step_validation_msg.replace("[should_min]", lowerNearest);
                step_validation_msg = step_validation_msg.replace("[should_next]", upperNearest);
            }

            
            step_validation_msg = step_validation_msg.replace('"[product_name]"', product_name);
            step_validation_msg = step_validation_msg.replace("[product_name]", product_name);

            

            // Check if the input is within the valid range
            if (inputValue < min || inputValue > max || (inputValue - min) % step !== 0) {
                // var step_validation_msg = 'Nearest valid values are ' + lowerNearest + ' and ' + upperNearest;
                this.setCustomValidity(step_validation_msg);
            } else {
                // Clear custom validity message if input is valid
                this.setCustomValidity('');
            }
        });

        /**
         * New added 
         * First time, It was handle from Min_Max_Controller::single_variation_handle()
         * currently that method is not need.
         * 
         * We will handle it from javascript actually.
         */
        $(document.body).on('change','offform.variations_form.cart input.variation_id',function(){
            var min,max,step,basic;
            var in_stock, stock_msg;
            var form = $(this).closest('form.variations_form.cart');
            form.find('.wcmmq-custom-stock-msg').remove();
            var qty_box = form.find('input.input-text.qty.text');
            var variation_id = $(this).val();
            variation_id = parseInt(variation_id);
            var variation_pass = variation_id > 0;
            if( ! variation_pass ){
                return;
            }
            var product_variations = form.data('product_variations');

            var gen_product_variations = new Array();
            $.each(product_variations, function(index, eachVariation){
                
                var this_variation_id = eachVariation['variation_id'];
                if( this_variation_id == variation_id){
                    in_stock = eachVariation['is_in_stock'];
                    stock_msg = eachVariation['availability_html'];
                    
                    console.log(eachVariation['is_in_stock']);
                    min = eachVariation['min_value'];
                    max = eachVariation['max_value'];
                    step = eachVariation['step'];
                    basic = min;

                    if( ! in_stock){
                        form.find('.single_variation_wrap').prepend('<div class="wcmmq-custom-stock-msg">' + stock_msg + '</div>');
                        min = 0;
                        max = 0;
                        basic = 0;
                        step = 0;
                    }
                    console.log(eachVariation);
                }
                // console.log(min,max,step,basic);
                if(min || ! in_stock){
                    console.log(min,max,step,basic);

                    var lateSome = setInterval(function(){
                        qty_box.attr({
                            min:min,
                            max:max,
                            step:step,
                            value:min
                        });
    
                        qty_box.val(basic).trigger('change');
                        clearInterval(lateSome);
                    },500);
                }
                
                // gen_product_variations[eachVariation->variation_id]
            });
        });


        //End of Custom Worl *******************/

        function distributeMinMax(variation_id,variation_data,qty_boxWPT){
            if(typeof variation_id !== 'undefined' && variation_id !== ''  && variation_id !== ' '){
                var min,max,step,basic;

                min = variation_data[variation_id]['min_quantity'];
                if(typeof min === 'undefined'){
                    return false;
                }

                max = variation_data[variation_id]['max_quantity'];
                step = variation_data[variation_id]['step_quantity'];

                basic = min;
                var lateSome = setInterval(function(){

                    qty_boxWPT.attr({
                        min:min,
                        max:max,
                        step:step,
                        value:min
                    });
                    qty_boxWPT.val(basic).trigger('change');
                    clearInterval(lateSome);
                },500);

            }
        }
        /**
             * It's our custom input box with text type
             * and we will transfer this text to main input(number) with convert comma to dot
             * 
             * @since 3.5.2
             */
        $(document.body).on('keyup','.wcmmq-second-input-box',function(Event){
                
            /**
             * First, I will findout, If any user click on
             * up or down arrow.
             * So that, we can set behavier like number input box.
             */
            var arrowPress = false;
            if(typeof Event === 'object' && typeof Event.originalEvent === 'object'){
                var originalEvent = Event.originalEvent;
                if(originalEvent.keyCode === 38 || originalEvent.code === 'ArrowUp'){
                      arrowPress = 'ArrowUp';
                }else if(originalEvent.keyCode === 40 || originalEvent.code === 'ArrowDown'){
                    arrowPress = 'ArrowDown';
                }

            }
            
            /**
             * Checking Down/Up arrow button
             * If not click on up or down arrow button
             * and if click on any number, then this bellow code will write in our 
             * main input(number) box with . or , 
             * 
             * @since3.5.2
             */
            if( !arrowPress ){
                var parentQuantity = $(this).parents('.quantity');
                var secondInputVal = $(this).val();
                var secondValWithDot = secondInputVal.replace(/,/g, '.');
                parentQuantity.find('.wcmmq-main-input-box').val(secondValWithDot);
            }else{
                var secondInboxObject = $(this);
                Event.preventDefault();
                plusMinusOnArrowCalculate(arrowPress,secondInboxObject);
            }
        });
        /**
         * ONLY for coman decimal separator, Not for else
         * 
         * First requirement:
         * It will work ONLY a User enable comma as Decimal
         * Otherwise, this is not will impact any more.
         * 
         * @since 3.5.2
         * 
         */
        function plusMinusOnArrowCalculate(type,secondInboxObject){

            var qty = secondInboxObject.closest('.wcmmq-coma-separator-activated').find('input.input-text.qty.text.wcmmq-main-input-box');
            // Read value and attributes min, max, step.
            var val = parseFloat(qty.val());
            var max = parseFloat(qty.attr("max"));
            var min = parseFloat(qty.attr("min"));
            var step = parseFloat(qty.attr("step"));
            console.log(min,val,max,step);

            if( type === 'ArrowUp'){
                if (val === max){
                    return false;
                }
                    
                if (isNaN(val)) {
                    qty.val(step).trgger('change');
                    return false;
                }

                qty.val(val + step);
            }else if( type === 'ArrowDown'){
                if (val === min){
                    return false;
                }
                if (isNaN(val)) {
                    qty.val(min).trgger('change');
                    return false;
                }
                if (val - step < min) {
                    qty.val(min);
                } else {
                    qty.val(val - step);
                }
            }

            qty.val(Math.round(qty.val() * 100000) / 100000);
            qty.trigger("change");
        }

        /**
         * this will only output 2 digit 
         * Especially solved for OceanWP theme
         */
        
        function CheckDecimal(inputtxt) { 
            if(!/^[-+]?[0-9]+\.[0-9]+$/.test(inputtxt)) { 
                return true;
            } else { 
                return false;
            }
        }
        var qty_box,qty_box_selector, qty_value, formatted_value;
        qty_box_selector = '.qib-button-wrapper .quantity input.input-text.qty.text, .single-product div.product form.cart .quantity input[type=number], .single-product div.product form.cart .quantity input[type=number]';
        $(document.body).on('change',qty_box_selector,function(){
            qty_value = $(this).val();
            
            if( decimal_separator != '.' ){
                console.log(decimal_separator);
                qty_value = qty_value.replace(/\./g, decimal_separator);
            }

            $(this).parents('.quantity').find('.wcmmq-second-input-box').val(qty_value);
        });
        
        // this may not work. we need to check the classs 
        // qty_box = $('.qib-button-wrapper .quantity input.input-text.qty.text, .single-product div.product form.cart .quantity input[type=number], .single-product div.product form.cart .quantity input[type=number]');
        
        // qty_box.on('change', function(){
        //     qty_value = $(this).val();

        //     if( decimal_separator === ',' ){
        //         qty_value = qty_value.replace(/\./g, ',');
        //         // $(this).parents('.quantity').find('.wcmmq-second-input-box').val(qty_value);
        //     }

        //     $(this).parents('.quantity').find('.wcmmq-second-input-box').val(qty_value);
        // });
        
    });


})(jQuery);
