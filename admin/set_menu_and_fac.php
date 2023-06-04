<?php

/**
 * Adding menu as WooCommerce's menu's Submenu
 * check inside Woocommerce Menu
 * 
 * @since 1.0
 */
function wcmmq_add_menu(){
    global $admin_page_hooks;
    $capability = apply_filters( 'wcmmq_menu_capability', 'manage_woocommerce' );
    
    add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', $capability, 'wcmmq_min_max_step', 'wcmmq_faq_page_details' );

}
add_action( 'admin_menu','wcmmq_add_menu' );

/**
 * Faq Page for WC Min Max Quantity
 */
function wcmmq_faq_page_details(){


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
    
?>

<div class="wrap wcmmq_wrap ultraaddons">
    <h1 class="wp-heading "></h1>
    <h1 class="ca-main-header-branding">
        <img src="<?php echo esc_url( $min_max_img ); ?>" class="wcmmq-brand-logo">
        <?php _e("Min Max Quantity & Step Control for WooCommerce by CodeAstrology", "wcmmq");?>
    </h1>
    <?php 
        // wcmmq_social_links(); 
    ?>
    <div class="fieldwrap">
        <?php
            do_action( 'wcmmq_before_form' );
        ?>
        <form action="#wcmmq-supported-terms" method="POST" id="wcmmq-main-configuration-form">
            <div class="ultraaddons-panel">
                <h2 class="with-background ca-branding-header"><?php echo esc_html__( 'Settings (Universal)', 'wcmmq' ); ?></h2>
                <table class="wcmmq_config_form">

                    <tr>
                        <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]"> <?php echo esc_html__( 'Minimum Quantity', 'wcmmq' ); ?></label></th>
                        <td>
                            <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]" class="ua_input_number config_min_qty" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'min_quantity']; ?>"  type="number" step=any>
                            <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/'); ?>
                        </td>

                    </tr>

                    <tr>
                        <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]"><?php echo esc_html__('Maximum Quantity','wcmmq');?></label></th>
                        <td>
                            <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]" class="ua_input_number config_max_qty" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'max_quantity']; ?>"  type="number" step=any>
                            <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/'); ?>
                        </td>

                    </tr>

                    <tr>
                        <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]"><?php echo esc_html__('Quantity Step','wcmmq');?></label></th>
                        <td>
                            <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]" class="ua_input_number" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'product_step']; ?>"  type="number" step=any>
                            <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/'); ?>
                        </td>

                    </tr>
                    <?php
                    //At this moment, no need it
                    // $exist_dfl_qty = $saved_data[WC_MMQ_PREFIX . 'default_quantity'] ?? false;
                    $default_qty = apply_filters( 'wcmmq_default_qty_option', false, $saved_data );
                    if( $default_qty ){
                    ?>
                    <tr>
                        <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]"><?php echo esc_html__('Default Quantity','wcmmq');?> <span class="hightlighted_text"><?php echo esc_html__('(Optional)','wcmmq');?></span></label></th>
                        <td>
                            <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]" class="ua_input_number" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'default_quantity']; ?>"  type="number" step=any>
                            <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/'); ?>
                            <p style="color: #228b22;">
                            It's shoold empty, If you don't know, what is this.
                            </p>
                        </td>

                    </tr>
                    <?php
                    }
                    if( wc_get_price_decimal_separator() == ',' ){ ?>
                    <tr>
                        <?php
                        
                        $decimal_separator = $saved_data['decimal_separator'] ?? '.';
                        ?>
                        <th><label for="data[decimal_separator]"><?php echo esc_html__('Quantity Decimal Separator','wcmmq');?> <span class="hightlighted_text"><?php echo esc_html__('(Optional)','wcmmq');?></span></label></th>
                        <td>
                            <select name="data[decimal_separator]" id="data[decimal_separator]" class="ua_select">
                                <option value="." <?php echo esc_attr( $decimal_separator == '.' ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Dot (.)', 'wcmmq' ); ?></option>
                                <option value="," <?php echo esc_attr( $decimal_separator == ',' ? 'selected' : '' ); ?>><?php echo esc_html__( 'Comma (,)', 'wcmmq' ); ?></option>
                            </select>
                            
                        </td>

                    </tr>
                    <?php } ?>
                    

                    <!-- 
                        * Will add quantity box on archive pages
                        * @ since 3.6.0
                        * @ Author Fazle Bari 
                        -->
                        <tr>
                        <?php $quantiy_box_archive = isset( $saved_data['quantiy_box_archive' ] ) && $saved_data['quantiy_box_archive' ] == '1' ? 'checked' : false; ?>
                        <th><label for="data[quantiy_box_archive]"><?php echo esc_html__('Archive Quantiy box','wcmmq');?></label></th>
                        <td>
                            <label class="switch">
                                <input value="1" name="data[quantiy_box_archive]"
                                    <?php echo $quantiy_box_archive; /* finding checked or null */ ?> type="checkbox" id="quantiy_box_archive">
                                <div class="slider round"><!--ADDED HTML -->
                                    <span class="on"><?php echo esc_html__('ON','wcmmq');?></span><span class="off"> <?php echo esc_html__('OFF','wcmmq');?></span><!--END-->
                                </div>
                            </label><?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/add-quantity-box-on-shop-page/'); ?>
                            <p style="color: #228b22;">
                            For ajax add to cart, Enable from <strong>WooCommerce->Settings->Products->Add to cart behaviour</strong>.<br>
                            For Variable product Quantity Box with Variation change box. Need premium version.<br>
                            If you need Plus Minus Button for your Quantity Box install <a href="https://wordpress.org/plugins/wc-quantity-plus-minus-button/" target="_blank">Quantity Plus Minus Button for WooCommerce by CodeAstrology</a>
                            </p>
                        </td>

                    </tr>

                    <tr>
                        <?php $disable_order_page = isset( $saved_data[ WC_MMQ_PREFIX . 'disable_order_page' ] ) && $saved_data[ WC_MMQ_PREFIX . 'disable_order_page' ] == '1' ? 'checked' : false; ?>
                        <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>disable_order_page]"><?php echo esc_html__('Order Page (Condition)','wcmmq');?></label></th>
                        <td>
                            <label class="switch">
                                <input value="1" name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>disable_order_page]"
                                    <?php echo $disable_order_page; /* finding checked or null */ ?> type="checkbox" id="_wcmmq_disable_order_page">
                                <div class="slider round"><!--ADDED HTML -->
                                    <span class="on"><?php echo esc_html__('ON','wcmmq');?></span><span class="off"> <?php echo esc_html__('OFF','wcmmq');?></span><!--END-->
                                </div>
                            </label><?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-conditions-on-woocommerce-order-page/'); ?>
                        </td>

                    </tr>
                    
                    <?php
                    /**
                     * Obviously need tr and td here
                     * 
                     */
                    do_action( 'wcmmq_setting_bottom_row', $saved_data );
                    ?>

                    
                </table>
                <?php do_action( 'wcmmq_offer_here' ); ?>
                <div class="ultraaddons-button-wrapper">
                    <button name="configure_submit" class="button-primary primary button"><?php echo esc_html__('Save All','wcmmq');?></button>
                </div>
                <?php
                $time = time();
                $tar_time = strtotime('11/25/2022');
                if($time < $tar_time){
                    $img = WC_MMQ_BASE_URL . 'assets/images/offer/black-friday-notice.png';
                    ?>
                    <a class="sort-time-offer-wcmmq" href="https://codeastrology.com/coupons/" target="_blank">
                        <img src="<?php echo esc_attr( $img ); ?>" style="max-width: 100%;height:auto;width:auto;">
                    </a>
                    <?php
                }
                
                // if()
                ?>
            </div>
        
                <?php 
                
                /**
                 * @Hook Action: wcmmq_form_panel
                 * To add new panel in Forms
                 * @since 1.8.6
                 */
                do_action( 'wcmmq_form_panel', $saved_data );
                ?>
            
        
            <div class="ultraaddons-panel" id="wcmmq-supported-terms">
                <h2 class="with-background black-background"> <?php echo esc_html__('Supported Terms','wcmmq');?></h2>
                <?php
                
                    $term_lists_temp = get_object_taxonomies('product','objects');

                    $wcmmq_all_terms= apply_filters( 'wcmmq_all_terms', false, $term_lists_temp, $saved_data );
                    if( $wcmmq_all_terms ){
                        $term_lists = $term_lists_temp;
                    }else{
                        $term_lists['product_cat']=$term_lists_temp['product_cat'];
                    }

                    $supported_terms = isset( $saved_data['supported_terms'] ) ?$saved_data['supported_terms'] : array( 'product_cat' );
                    $ourTermList = $select_option = false;
                    if( is_array( $term_lists ) && count( $term_lists ) > 0 ){
                        foreach( $term_lists as $trm_key => $trm_object ){
                            $selected =  ( !$supported_terms && $trm_key == 'product_cat' ) || ( is_array( $supported_terms ) && in_array( $trm_key, $supported_terms ) ) ? 'selected' : false;
                            //( !$supported_terms && $trm_key == 'product_cat' ) ||
                            //var_dump($trm_key,$selected);
                            if( $trm_object->labels->singular_name == 'Tag' && $trm_key !== 'product_tag' ){
                                $value = $trm_key;
                                $select_option .= "<option value='" . esc_attr( $trm_key ) . "' " . esc_attr( $selected ) . ">" . $trm_key . "</option>";
                            }else{
                                $value = $trm_object->labels->singular_name;
                                $select_option .= "<option value='" . esc_attr( $trm_key ) . "' " . esc_attr( $selected ) . ">" . $trm_object->labels->singular_name . "</option>";
                            }
                            if( $selected ){
                            $ourTermList[$trm_key] = $value; 
                            }
                        }
                    }

                    ?>
                <table class="wcmmq_config_form">
                    <tr>
                        <th><label for=""><?php echo esc_html__('Choose Terms','wcmmq');?></label></th>
                        <td>
                            <?php  ?>
                            <select name="data[supported_terms][]" data-name="supported_terms" class="ua_input_select" id="wcmmq_supported_terms" multiple>
                                <?php
                                echo $select_option;
                                ?>
                            </select><?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-conditions-to-a-specific-category/'); ?>
                            <p class="wcmmq_terms_promotion">
                            <?php 
                                if( ! defined( 'WC_MMQ_PRO_VERSION' ) ){
                            ?>
                                    <?php echo esc_html__('For Mulitple Terms,','wcmmq');?> <a href="https://codeastrology.com/min-max-quantity/pricing/"><?php echo esc_html__('Upgrade to PRO','wcmmq');?></a>    
                            <?php
                                };
                            ?>
                            </p>
                        </td>

                    </tr>

                </table> 
                
            <div class="ultraaddons-panel inside-panel">
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

                            include 'includes/terms_condition.php';
                        }


                    ?>                    
                </div><!-- /.wcmmq-terms-wrapper -->                

            </div>
                    <div class="ultraaddons-button-wrapper">
                        <button name="configure_submit" class="button-primary primary button" id="wcmmq_form_submit_button"> <?php echo esc_html__('Save All','wcmmq');?></button>
                    </div>
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
                        'desc'  => __('Available shortcode [min_quantity],[max_quantity],[product_name],[step_quantity],[variation_name]','wcmmq' ),
                    ],
                    
                    'msg_max_limit' => [
                        'title' => __('Maximum Quantity Validation Message','wcmmq' ),
                        'desc'  => __('Available shortcode [current_quantity][min_quantity],[max_quantity],[product_name],[step_quantity],[variation_name]','wcmmq' ),
                    ],
                    'msg_max_limit_with_already' => [
                        'title' => __('Maximum Quantity Validation Message','wcmmq' ),
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
            <div class="section ultraaddons-button-wrapper ultraaddons-panel no-background wcmmq-submit-button">
                <button name="configure_submit" class="button-primary primary button"> <?php echo esc_html__('Save Change','wcmmq');?></button>
                <button name="reset_button" class="button button-default" onclick="return confirm('If you continue with this action, you will reset all options in this page.\nAre you sure?');"><?php echo esc_html__( 'Reset Default', 'wcmmq' ); ?></button>
            </div>
                    
        </form>
        <div class="wpmmq-form-bottom wpmmq-plugin-recommended-wrapper">
            <?php 
            /**
             * Added Recommendation plugin notice over here
             */
            do_action( 'wcmmq_form_bottom', $saved_data );
            
            ?>
        </div>
        <?php 
            wcmmq_social_links(); 
            wcmmq_submit_issue_link();
        ?>
    </div>
</div>  

<?php
}

function wcmmq_load_custom_wp_admin_style() {
    
    /**
     * Select2 CSS file including. 
     * 
     * @since 1.0.3
     */    
    wp_enqueue_style( 'select2-css', WC_MMQ_BASE_URL . 'assets/css/select2.min.css' );

    /**
     * Select2 jQuery Plugin file including. 
     * Here added min version. But also available regular version in same directory
     * 
     * @since 1.9
     */
    wp_enqueue_script( 'select2', WC_MMQ_BASE_URL . 'assets/js/select2.full.min.js', array( 'jquery' ), '4.0.5', true );

    
    wp_register_script( 'wcmmq-admin-script', WC_MMQ_BASE_URL . 'assets/js/admin.js', array( 'jquery','select2' ), WC_MMQ::getVersion(), true );
    wp_enqueue_script( 'wcmmq-admin-script' );
    
    wp_register_style( 'wcmmq_css', WC_MMQ_BASE_URL . 'assets/css/admin.css', false, WC_MMQ::getVersion() );
    wp_enqueue_style( 'wcmmq_css' );

    wp_register_style( 'ultraaddons-common-css', WC_MMQ_BASE_URL . 'assets/css/admin-common.css', false, WC_MMQ::getVersion() );
    wp_enqueue_style( 'ultraaddons-common-css' );

    
        
}
add_action( 'admin_enqueue_scripts', 'wcmmq_load_custom_wp_admin_style' );
