(function($){
    'use strict';
    $(document).ready(function(){
        //alert(22);
        $('.ua_input_select,.wcmmq_select_terms').select2();
        
        /**
         * Support terms -> on after change,
         * form will be submit
         */
        $(document.body).on('change','#wcmmq_supported_terms',function(){
            $('#wcmmq_form_submit_button').trigger('click');
            //$('#wcmmq-main-configuration-form').submit(); //Didn't work this
        });
    });
})(jQuery);