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
    
?>
<div class="wrap wcmmq_wrap ultraaddons">
    <h1 class="wp-heading"><?php _e("Woocommerce Min Max Step Control", "wcmmq");?></h1>
    <div class="fieldwrap">
        <?php
        do_action( 'wcmmq_before_form' );
        ?>
        <form action="#wcmmq-supported-terms" method="POST" id="wcmmq-main-configuration-form">
                <div class="ultraaddons-panel">
                    <h2 class="with-background">Settings (Universal)</h2>
                    <table class="wcmmq_config_form">

                        <tr>
                            <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]">Minimum Quantity</label></th>
                            <td>
                                <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]" class="ua_input_number config_min_qty" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'min_quantity']; ?>"  type="number" step=any>
                            </td>

                        </tr>

                        <tr>
                            <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]">Maximum Quantity</label></th>
                            <td>
                                <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]" class="ua_input_number config_max_qty" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'max_quantity']; ?>"  type="number" step=any>
                            </td>

                        </tr>

                        <tr>
                            <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]">Quantity Step</label></th>
                            <td>
                                <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]" class="ua_input_number" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'product_step']; ?>"  type="number" step=any>
                            </td>

                        </tr>

                        <tr>
                            <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]">Default Quantity <span class="hightlighted_text">(Optional)</span></label></th>
                            <td>
                                <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]" class="ua_input_number" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'default_quantity']; ?>"  type="number" step=any>
                            </td>

                        </tr>
                        <tr>
                            <?php $disable_order_page = isset( $saved_data[ WC_MMQ_PREFIX . 'disable_order_page' ] ) && $saved_data[ WC_MMQ_PREFIX . 'disable_order_page' ] == '1' ? 'checked' : false; ?>
                            <th><label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>disable_order_page]">Order Page (Condition)</label></th>
                            <td>
                                <label class="switch">
                                    <input value="1" name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>disable_order_page]"
                                        <?php echo $disable_order_page; /* finding checked or null */ ?> type="checkbox" id="_wcmmq_disable_order_page">
                                    <div class="slider round"><!--ADDED HTML -->
                                        <span class="on">ON</span><span class="off">OFF</span><!--END-->
                                    </div>
                                </label>
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
                        <button name="configure_submit" class="button-primary primary button">Save All</button>
                    </div>
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
                <h2 class="with-background black-background">Supported Terms</h2>
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
                            <th><label for="">Choose Terms</label></th>
                            <td>
                                <?php  ?>
                                <select name="data[supported_terms][]" data-name="supported_terms" class="ua_input_select" id="wcmmq_supported_terms" multiple>
                                    <?php
                                    echo $select_option;
                                    ?>
                                </select>
                                <p class="wcmmq_terms_promotion">
                                <?php 
                                    if( ! defined( 'WC_MMQ_PRO_VERSION' ) ){
                                ?>
                                    For Mulitple Terms, <a href="https://codeastrology.com/min-max-quantity/pricing/">Upgrade to PRO</a>    
                                <?php
                                    };
                                ?>
                                </p>
                            </td>

                        </tr>

                    </table> 
                
                <div class="ultraaddons-panel inside-panel">
                <h2 class="with-background">Edit Terms</h2>
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
                        <button name="configure_submit" class="button-primary primary button" id="wcmmq_form_submit_button">Save All</button>
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
                    <li class="edit" data-target="term_table_' + id + '">Edit</li>\n\
                    <li class="delete">Delete</li>\n\
                 </ul>\n\
                 <div class="product_cat">';
        html += '<table id="term_table_' + id + '">';
        html += tr + th; 
        html += '<label>Minimum Quantity</label>';
        html += thC + td;
        html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_min]" value=""  type="number" step=any>';
        html += tdC + trC + tr + th; 
        html += '<label>Maximum Quantity</label>';
        html += thC + td;
        html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_max]" value=""  type="number" step=any>';
        html += tdC + trC + tr + th;
        html += '<label>Step Quantity</label>';
        html += thC + td;
        html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_step]" value=""  type="number" step=any>';
        html += tdC + trC + tr + th;
        html += '<label>Default Quantity</label>';
        html += thC + td;
        html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_default]" value=""  type="number" step=any>';
        html += tdC + trC;
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
                        'title' => 'Minimum Quantity Validation Message',
                        'desc'  => 'Available shortcode [min_quantity],[max_quantity],[product_name]',
                    ],
                    
                    'msg_max_limit' => [
                        'title' => 'Maximum Quantity Validation Message',
                        'desc'  => 'Available shortcode [current_quantity][min_quantity],[max_quantity],[product_name]',
                    ],
                    'msg_max_limit_with_already' => [
                        'title' => 'Maximum Quantity Validation Message',
                        'desc'  => 'Available shortcode [current_quantity][min_quantity],[max_quantity],[product_name]',
                    ],
                    'min_qty_msg_in_loop' => [
                        'title' => 'Minimum Quantity message for shop page',
                        'desc'  => 'Available shortcode [min_quantity],[max_quantity],[product_name]',
                    ],
                    'step_error_valiation' => [
                        'title' => 'Step validation error message',
                        'desc'  => 'Available shortcode [should_min],[should_next]',
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
            <div class="section ultraaddons-button-wrapper ultraaddons-panel no-background">
                <button name="configure_submit" class="button-primary primary button">Save Change</button>
                <button name="reset_button" class="button button-default" onclick="return confirm('If you continue with this action, you will reset all options in this page.\nAre you sure?');">Reset Default</button>
            </div>
                    
        </form>
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
