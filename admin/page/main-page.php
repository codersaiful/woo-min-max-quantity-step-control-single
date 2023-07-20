<?php

if( isset( $_POST['data'] ) && isset( $_POST['reset_button'] ) ){
    //Reset 
    $data = WC_MMQ::getDefaults();
    //var_dump($value);
    update_option( WC_MMQ_KEY, $data );
    echo '<div class="updated inline"><p>Reset Successfully</p></div>';
}else if( isset( $_POST['data'] ) && isset( $_POST['configure_submit'] ) ){

    //configure_submit
    $values = ( is_array( $_POST['data'] ) ? $_POST['data'] : false );
    
    $data = $final_data = array();
    if( is_array( $values ) && count( $values ) > 0 ){
        foreach( $values as $key=>$value ){
            if( empty( $value ) ){
               $data[$key] = false; 
            }else{
               $data[$key] = $value;  
            }
        }
    }else{
        $data = WC_MMQ::getDefaults();
    }
    
    if( !$data[WC_MMQ_PREFIX . 'min_quantity'] && $data[WC_MMQ_PREFIX . 'min_quantity'] != 0 &&  $data[WC_MMQ_PREFIX . 'min_quantity'] !=1 && $data[WC_MMQ_PREFIX . 'max_quantity'] <= $data[WC_MMQ_PREFIX . 'min_quantity'] ){
        $data[WC_MMQ_PREFIX . 'max_quantity'] = $data[WC_MMQ_PREFIX . 'min_quantity'] + 5;
        echo '<div class="error notice"><p>Maximum Quantity can not be smaller, So we have added 5</p></div>';
    }
    if( !$data[WC_MMQ_PREFIX . 'product_step'] || $data[WC_MMQ_PREFIX . 'product_step'] == '0' || $data[WC_MMQ_PREFIX . 'product_step'] == 0 ){
       $data[WC_MMQ_PREFIX . 'product_step'] = 1; 
    }
    
    if( !$data[WC_MMQ_PREFIX . 'min_quantity'] || $data[WC_MMQ_PREFIX . 'min_quantity'] == '0' || $data[WC_MMQ_PREFIX . 'min_quantity'] == 0 ){
       $data[WC_MMQ_PREFIX . 'min_quantity'] = '0'; 
    }
    $data[WC_MMQ_PREFIX . 'default_quantity'] = isset( $data[WC_MMQ_PREFIX . 'default_quantity'] ) && $data[WC_MMQ_PREFIX . 'default_quantity'] >= $data[WC_MMQ_PREFIX . 'min_quantity'] && ( empty( $data[WC_MMQ_PREFIX . 'max_quantity'] ) || $data[WC_MMQ_PREFIX . 'default_quantity'] <= $data[WC_MMQ_PREFIX . 'max_quantity'] ) ? $data[WC_MMQ_PREFIX . 'default_quantity'] : false;
    
    //plus minus checkbox data fixer
    $data[ WC_MMQ_PREFIX . 'qty_plus_minus_btn' ] = !isset( $data[ WC_MMQ_PREFIX . 'qty_plus_minus_btn' ] ) ? 0 : 1;
    
    if(is_array( $data ) && count( $data ) > 0 ){
        foreach($data as $key=>$value){
            if( is_string( $value ) ){
                $val = str_replace('\\', '', $value );
            }else{
                $val = $value;
            }
            
            $final_data[$key] = $val;
        }
    }
    
    
    //set default value false for _cat_ids
    $final_data['_cat_ids'] = isset( $final_data['_cat_ids'] ) ? $final_data['_cat_ids'] : false;
    update_option( WC_MMQ_KEY, $final_data);
    echo '<div class="updated"><p>Successfully Updated</p></div>';
    //echo  ! $data[WC_MMQ_PREFIX . 'default_quantity'] ? '<div class="error warning"><p>But Default Quanity should gatter then Min Quantity And less then Max Quantity. <b>Only is you set Default Quantity</b></p></div>' : false;
}

$saved_data = WC_MMQ::getOptions();

$min_max_img = WC_MMQ_BASE_URL . 'assets/images/brand/social/min-max.png';

//TOPBAR INCLUDE HERE
include 'main-page/topbar.php';
?>





<div class="wrap wcmmq_wrap wcmmq-content">
    <?php 
        $is_pro = $this->is_pro;
        if( ! $is_pro ){
            include 'main-page/premium-link-header.php'; 
        }
        // wcmmq_social_links();
        // var_dump($this);
    ?>
    <h1 class="wp-heading "></h1>
    <div class="fieldwrap">
        <?php
            // do_action( 'wcmmq_before_form' );
        ?>
        <div class="wcmmq-section-panel no-background">
            <a class="wcmmq-btn wcmmq-has-icon" href="#"><span><i class="wcmmq_icon-ok"></i></span>Link</a>
            <button class="wcmmq-btn wcmmq-has-icon"><span><i class="wcmmq_icon-ok"></i></span>Save Change</button>
            <button class="wcmmq-btn reset wcmmq-has-icon"><span><i class="wcmmq_icon-ok"></i></span>Save Change</button>
            
        </div>
        
        <form action="#wcmmq-supported-terms" method="POST" id="wcmmq-main-configuration-form">
            <div class="wcmmq-section-panel universal-settings ultraaddons" id="wcmmq-universal-settings">
                <?php include 'main-page/universal-settings.php'; ?>
            </div>
        
            <?php 
            
            /**
             * @Hook Action: wcmmq_form_panel
             * To add new panel in Forms
             * @since 1.8.6
             */
            do_action( 'wcmmq_form_panel', $saved_data );
            ?>
            

        
            <div class="wcmmq-section-panel supported-terms" id="wcmmq-supported-terms">
                
            
            </div>
            
            <div class="wcmmq-section-panel inside-panel">
                <h2 class="with-background"> <?php echo esc_html__('Edit Terms','wcmmq');?></h2>
                <div class="wcmmq-terms-wrapper">
                    <?php

                        /**
                         * Automatically display all terms in Set on Terrms
                         */
                        $support_all_terms = apply_filters( 'wcmmq_display_all_terms', false, $saved_data );
                        if( $support_all_terms ){
                            $term_lists = get_object_taxonomies('product','objects');
                            //var_dump($term_lists);
                            $ourTermList = false;
                            foreach( $term_lists as $trm_key => $trm_object ){
                                if( $trm_object->labels->singular_name == 'Tag' && $trm_key !== 'product_tag' ){
                                    $ourTermList[$trm_key] = $trm_key;
                                }else{
                                    $ourTermList[$trm_key] = $trm_object->labels->singular_name;
                                }
                            }
                        }

                        $term_lists = apply_filters( 'wcmmq_terms_list', $ourTermList, $saved_data );

                        $args = array(
                            'hide_empty'    => false, 
                            'orderby'       => 'count',
                            'order'         => 'DESC',
                        );
                        $_term_lists = isset( $saved_data['terms'] ) && is_array( $saved_data['terms'] ) ? array_merge( $saved_data['terms'], $term_lists ) : $term_lists;

                        foreach( $_term_lists as $key => $each ){
                            $term_key = $key;
                            $term_name = !empty( $term_lists[$key] ) ? $term_lists[$key] : $key;

                            $term_obj = get_terms( $term_key, $args );

                            $selected_term_ids = isset( $saved_data['terms'][$term_key] ) && !empty( $saved_data['terms'][$term_key] ) ? $saved_data['terms'][$term_key] : false;
                            $selected_term_ids = wcmmq_term_ids_wpml( $selected_term_ids, $key );

                            include WC_MMQ_BASE_DIR . 'admin/includes/terms_condition.php';
                        }


                    ?>                    
                </div><!-- /.wcmmq-terms-wrapper -->                

            </div>
            
            <script>
            jQuery(document).ready(function($){
                $(document).on('click','.add_terms_button', function(e){
                    
                    e.preventDefault();
                    var term_key = $(this).attr('data-term_key');
                    var id = $('.wcmmq_select_terms.' + term_key).val();
                        var term_name  = $('.wcmmq_select_terms.' + term_key + ' option[value="' + id + '"]').text();
                    if( $('#wcmmq_terms_' + term_key + '_' + id).length > 0 ){
                        alert("Already Added");
                        return;
                    }
                    var html = '';
                    var td, tdC, th, thC, tr, trC;
                    td = '<td>';
                    tdC = '</td>';
                    th = '<th>';
                    thC = '</th>'
                    tr = '<tr>';
                    trC = '</tr>';
                    html += '<div id="wcmmq_terms_' + term_key + '_' + id + '" class="wcmmq_each_terms"  data-term_key="' + term_key + '" data-term_id="' + id + '">\n\
                            <ul class="wcmmq_each_terms_header" data-target="term_table_' + id + '">\n\
                                <li class="label">' + term_name + '<small>' + term_key + '</small></li>\n\
                                <li class="edit" data-target="term_table_' + id + '"><?php echo esc_html__( 'Edit', 'wcmmq' ); ?></li>\n\
                                <li class="delete"><?php echo esc_html__( 'Delete', 'wcmmq' ); ?></li>\n\
                            </ul>\n\
                            <div class="product_cat">';
                    html += '<table id="term_table_' + id + '">';
                    html += tr + th; 
                    html += '<label><?php echo esc_html__( 'Minimum Quantity', 'wcmmq' ); ?></label>';
                    html += thC + td;
                    html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_min]" value=""  type="number" step=any>';
                    html += tdC + trC + tr + th; 
                    html += '<label><?php echo esc_html__( 'Maximum Quantity', 'wcmmq' ); ?></label>';
                    html += thC + td;
                    html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_max]" value=""  type="number" step=any>';
                    html += tdC + trC + tr + th;
                    html += '<label><?php echo esc_html__( 'Step Quantity', 'wcmmq' ); ?></label>';
                    html += thC + td;
                    html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_step]" value=""  type="number" step=any>';
                    html += tdC + trC;

                    <?php
                    $default_qty = apply_filters( 'wcmmq_default_qty_option', false, $saved_data );
                    if( $default_qty ){
                    ?> 
                    html += tr + th;
                    html += '<label><?php echo esc_html__( 'Default Quantity', 'wcmmq' ); ?></label>';
                    html += thC + td;
                    html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_default]" value=""  type="number" step=any>';
                    html += tdC + trC;
                    <?php } ?>
                    html += '</table>';
                    html += '</div></div>';
                    $('.wcmmq_terms_wrapper.term_wrapper_' + term_key).prepend(html);
                });
                
                $(document).on('click','ul.wcmmq_each_terms_header',function(){
                    var table_id = $(this).attr('data-target');
                    console.log(table_id);
                    $('#' + table_id).toggle();
                });
                    
                // delete from list
                $('body').on('click', '.delete', function(){
                    //e.preventDefault();
                    $(this).parents('.wcmmq_each_terms').remove();
                });
                
                $( ".wcmmq_terms_wrapper, .wcmmq-terms-wrapper" ).sortable({
                    handle:this,//'.ultratable-handle'//this //.ultratable-handle this is handle class selector , if need '.ultratable-handle',
                });
                
                    //woocommerce_page_wcmmq_min_max_step 
                function wcmmqSelectItem(target, id) { // refactored this a bit, don't pay attention to this being a function
                    var option = $(target).children('[value='+id+']');
                    option.detach();
                    $(target).append(option).change();
                }
                $('.wcmmq_config_form select').select2();
                $('.wcmmq_config_form select').on('select2:select', function(e){
                wcmmqSelectItem(e.target, e.params.data.id);
                });    
            });
            </script>
                <?php 
                
                /**
                 * @Hook Action: wcmmq_form_panel
                 * To add new panel in Forms
                 * @since 1.8.6
                 */
                do_action( 'wcmmq_form_panel_before_message', $saved_data );

                $fields_arr = [
                    'msg_min_limit' => [
                        'title' => __('Minimum Quantity Validation Message','wcmmq' ),
                        'desc'  => __('Available shortcode [min_quantity],[max_quantity],[product_name],[step_quantity],[inputed_quantity],[variation_name]','wcmmq' ),
                    ],
                    
                    'msg_max_limit' => [
                        'title' => __('Maximum Quantity Validation Message','wcmmq' ),
                        'desc'  => __('Available shortcode [current_quantity][min_quantity],[max_quantity],[product_name],[step_quantity],[inputed_quantity],[variation_name]','wcmmq' ),
                    ],
                    'msg_max_limit_with_already' => [
                        'title' => __('Already Quantity Validation Message','wcmmq' ),
                        'desc'  => __('Available shortcode [current_quantity][min_quantity],[max_quantity],[product_name],[step_quantity],[variation_name]','wcmmq' ),
                    ],
                    'min_qty_msg_in_loop' => [
                        'title' => __('Minimum Quantity message for shop page','wcmmq' ),
                        'desc'  => __('Available shortcode [min_quantity],[max_quantity],[product_name],[step_quantity],[variation_name]','wcmmq' ),
                    ],
                    'step_error_valiation' => [
                        'title' => __('Step validation error message','wcmmq' ),
                        'desc'  => __('Available shortcode [should_min],[should_next],[product_name],[variation_name],[quantity],[min_quantity],[step_quantity]','wcmmq' ),
                    ],
            
                ];
            
                wcmmq_message_field_generator($fields_arr, $saved_data);
                
                /**
                 * @Hook Action: wcmmq_form_panel
                 * To add new panel in Forms
                 * @since 1.8.6
                 */
                do_action( 'wcmmq_form_panel_bottom', $saved_data );
                ?>
            <div class="section ultraaddons-button-wrapper wcmmq-section-panel no-background wcmmq-submit-button">
                <button name="configure_submit" class="button-primary primary button"> <?php echo esc_html__('Save Change','wcmmq');?></button>
                <button name="reset_button" class="button button-default" onclick="return confirm('If you continue with this action, you will reset all options in this page.\nAre you sure?');"><?php echo esc_html__( 'Reset Default', 'wcmmq' ); ?></button>
            </div>
                    
        </form>
        
        
        <!-- eta asole save all button er jonno. that's why display none  -->
        <div class="ultraaddons-button-wrapper">
                <button style="display: none;" name="configure_submit" class="button-primary primary button" id="wcmmq_form_submit_button"> <?php echo esc_html__('Save All','wcmmq');?></button>
        </div>
    </div>
</div> 