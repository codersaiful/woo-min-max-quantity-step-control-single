(function($){
    'use strict';
    $(document).ready(function(){
        
        $('.ua_input_select,.wcmmq_select_terms').select2();
        
        /**
         * Support terms -> on after change,
         * form will be submit
         */
         $(document.body).on('change','#wcmmq_supported_terms',function(){
            $('#wcmmq_form_submit_button').trigger('click');
        });
        
        $(document.body).on('click','.wcmmq-premium',function(){
            // alert(45454);
            var img_link = $(this).find('img').attr('src');
            var link = 'https://codeastrology.com/min-max-quantity/pricing/';
            window.open(img_link,'_blank');
            // window.location.href = 'https://codeastrology.com/min-max-quantity/pricing/';
        });
        
        $(document.body).on('submit', 'form#wcmmq-main-configuration-form', function (e){
            e.preventDefault();
            var min_val = $(this).find('.config_min_qty').val();
            var max_val = $(this).find('.config_max_qty').val();
            if(min_val!=''){
                //Just added parseInt() beacuse of data type was string.
                if (parseInt(min_val) > parseInt(max_val)) {
                    alert("Please make sure that your minimum quantity is smaller than maximum quantity!");
                    return false;
                }
            }

            let submitBtn = $(this).find('button.configure_submit');
            let submitBtnInForm = submitBtn.not('.float-btn');
            let submitBtnIcon = submitBtn.find('span i');
            submitBtn.find('strong.form-submit-text').text('Saving...');
            submitBtnIcon.attr('class', 'wcmmq_icon-spin5 animate-spin');
            // submitBtnIcon.attr('class', 'wcmmq_icon-floppy');
            
            var postURL = 'http://wpp.cm/wp-admin/admin.php?page=wcmmq-min-max-control';
            var other_data = $(this).serializeArray();
            
            // $.post(postURL, other_data, function(response){}).done(function(result){
            //     $('#wcmmq-live-support-area').html(result);
            //     $('#wcmmq-universal-settings').html(result);
                
            //     alert('Savvveeeeeed');
            // }).fail(function(){
            //     alert('FFFFFFFFaileddddddddd');
            // });

            console.log(other_data);
            // let data = {
            //     action: 'wcmmq_save_form',
            //     other_data: other_data
            // };

            // let ajax_url = WCMMQ_ADMIN_DATA.ajax_url;
            // $.ajax({
            //     type: 'POST',
            //     url: ajax_url,// + get_data,
            //     data: data,
            //     success:function(result){
            //         $('#wcmmq-live-support-area').html(result);
            //     }
            // });
        });

       
    });

    /** Save Floating button  **/
    var saveChangeText = 'Save';
    var btnHtml = '<div class="">';
    btnHtml += '<button type="submit" name="configure_submit" class="float-btn wcmmq-btn wcmmq-has-icon configure_submit"><span><i class="wcmmq_icon-floppy"></i></span><strong>' + saveChangeText + '</strong></button>';
    btnHtml += '</div>';
    //wcmmq-main-configuration-form

    var colSetsLen = $('form#wcmmq-main-configuration-form').length;
    if( colSetsLen > 0 ){
        $('#wcmmq-main-configuration-form').append(btnHtml);
    } 
   
   //var $elem = $('.float-section');
   $(window).on('scroll',function(){
    
    let targetElement = $('.float-btn');
    
    
    let bodyHeight = $('#wpbody').height();
    let scrollTop = $(this).scrollTop();
    let screenHeight = $(this).height();

    let configFormElement = $('form#wcmmq-main-configuration-form');
    if(configFormElement.length < 1) return;

    let conPass = bodyHeight - screenHeight - 100 - targetElement.height();
    let leftWill = configFormElement.width() - targetElement.width() - 20;
    

    // targetElement.css({
    //     left: leftWill,
    //     right: 'unset'
    // });
    if(scrollTop < conPass){
        targetElement.addClass('stick_on_scroll-on');
    }else{
        targetElement.removeClass('stick_on_scroll-on');
    }
    console.log(scrollTop,colSetsLen);
    if(scrollTop > 100 && colSetsLen > 0){
        targetElement.attr('id','stick_on_scroll-on');
    }else if(colSetsLen > 0){
        targetElement.removeAttr('id');
    }
    

});
})(jQuery);