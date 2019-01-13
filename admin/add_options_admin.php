<?php

/**
 * Min Max & Step panel for each Product's edit page
 * Field adding to Product Page
 * 
 * @since 1.0
 * @link https://docs.woocommerce.com/wc-apidocs/source-function-woocommerce_wp_text_input.html#14-79 Details of woocommerce_wp_text_input() from WooCommerce
 */
function wcmmq_s_add_field_in_panel(){
    $args = false;
    $args[] = array(
        'id'        =>  '_wcmmq_s_min_quantity',
        'name'        =>  '_wcmmq_s_min_quantity',
        'label'     =>   __('Min Quantity','wcmmq'),
        'class'     =>  'wcmmq_s_input',
        'type'      =>  'number',
        'desc_tip'  =>  true,
        'description'=> __('Enter Minimum Quantity for this Product','wcmmq'),
    );
    
    $args[] = array(
        'id'        =>  '_wcmmq_s_max_quantity',
        'name'        =>  '_wcmmq_s_max_quantity',
        'label'     =>  __('Max Quantity','wcmmq'),
        'class'     =>  'wcmmq_s_input',
        'type'      =>  'number',
        'desc_tip'  =>  true,
        'description'=> __('Enter Maximum Quantity for this Product','wcmmq'),
    );
    
    $args[] = array(
        'id'        =>  '_wcmmq_s_product_step',
        'name'        =>  '_wcmmq_s_product_step',
        'label'     =>  __('Quantity Step','wcmmq'),
        'class'     =>  'wcmmq_s_input',
        'type'      =>  'number',
        'desc_tip'  =>  true,
        'description'=> __('Enter quantity Step','wcmmq'),
    );
    
    foreach($args as $arg){
        woocommerce_wp_text_input($arg);
    }
}

add_action('woocommerce_product_options_wcmmq_s_minmaxstep','wcmmq_s_add_field_in_panel'); //Our custom action, which we have created to product_panel.php file

/**
 * To save and update our Data.
 * We have fixed , if anybody mismatch with min and max. Than max will be automatically increase 5 for now
 * In future we will add options, when can be change from options page
 * 
 * @param Int $post_id automatically come via woocommerce_process_product_meta as parameter.
 * return void
 */
function wcmmq_s_save_field_data( $post_id ){
    
    $_wcmmq_s_min_quantity = isset( $_POST['_wcmmq_s_min_quantity'] ) && is_numeric($_POST['_wcmmq_s_min_quantity']) ? sanitize_text_field($_POST['_wcmmq_s_min_quantity']) : false;
    $_wcmmq_s_max_quantity = isset( $_POST['_wcmmq_s_max_quantity'] ) && is_numeric($_POST['_wcmmq_s_max_quantity']) ? sanitize_text_field($_POST['_wcmmq_s_max_quantity']) : false;
    $_wcmmq_s_product_step = isset( $_POST['_wcmmq_s_product_step'] ) && is_numeric($_POST['_wcmmq_s_product_step']) ? sanitize_text_field($_POST['_wcmmq_s_product_step']) : false;
    if($_wcmmq_s_min_quantity && $_wcmmq_s_max_quantity && $_wcmmq_s_min_quantity > $_wcmmq_s_max_quantity){
        $_wcmmq_s_max_quantity = $_wcmmq_s_min_quantity + 5;
    }
    if( !$_wcmmq_s_product_step ){
        $_wcmmq_s_product_step = $_wcmmq_s_min_quantity;
    }
    
    //Updating Here
    update_post_meta( $post_id, '_wcmmq_s_min_quantity', esc_attr( $_wcmmq_s_min_quantity ) ); 
    update_post_meta( $post_id, '_wcmmq_s_max_quantity', esc_attr( $_wcmmq_s_max_quantity ) ); 
    update_post_meta( $post_id, '_wcmmq_s_product_step', esc_attr( $_wcmmq_s_product_step ) ); 
}
add_action( 'woocommerce_process_product_meta', 'wcmmq_s_save_field_data' );
