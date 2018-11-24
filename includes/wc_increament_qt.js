/* 
 * Only for Fronend Section
 * @since 1.0.0
 */


(function($) {
    $(document).ready(function() {
        $('body').on('change','input.input-text.qty.text.nothing.increament',function(){
            var min = $(this).attr('min');
            var range = min;
            var max = $(this).attr('max');
            
            //Set Click count
            console.log(this);
            
        });
        
    });
})(jQuery);
