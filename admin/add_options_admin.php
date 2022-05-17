<?php

/**
 * Min Max & Step panel for each Product's edit page
 * Field adding to Product Page
 * 
 * @since 1.0
 * @link https://docs.woocommerce.com/wc-apidocs/source-function-woocommerce_wp_text_input.html#14-79 Details of woocommerce_wp_text_input() from WooCommerce
 */
function wcmmq_add_field_in_panel(){
    $args = array();
    $args[] = array(
        'id'        =>  WC_MMQ_PREFIX. 'min_quantity',
        'name'        =>  WC_MMQ_PREFIX. 'min_quantity',
        'label'     =>  'Min Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Enter Minimum Quantity for this Product',
        'data_type' => 'decimal'
    );
    
    $args[] = array(
        'id'        =>  WC_MMQ_PREFIX. 'default_quantity',
        'name'        =>  WC_MMQ_PREFIX. 'default_quantity',
        'label'     =>  'Default Quantity (Optional)',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'It is an optional Number, If do not set, Product default quantity will come from Minimum Quantity',
        'data_type' => 'decimal'
    );
    
    $args[] = array(
        'id'        =>  WC_MMQ_PREFIX. 'max_quantity',
        'name'        =>  WC_MMQ_PREFIX. 'max_quantity',
        'label'     =>  'Max Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Enter Maximum Quantity for this Product',
        'data_type' => 'decimal'
    );
    
    $args[] = array(
        'id'        =>  WC_MMQ_PREFIX. 'product_step',
        'name'        =>  WC_MMQ_PREFIX. 'product_step',
        'label'     =>  'Quantity Step',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Enter quantity Step',
        'data_type' => 'decimal'
    );
    
    foreach($args as $arg){
        woocommerce_wp_text_input($arg);
    }
}

add_action('woocommerce_product_options_wcmmq_minmaxstep','wcmmq_add_field_in_panel'); //Our custom action, which we have created to product_panel.php file

/**
 * To save and update our Data.
 * We have fixed , if anybody mismathch with min and max. Than max will be automatically increase 5 for now
 * In future we will add options, when can be change from options page
 * 
 * @param Int $post_id automatically come via woocommerce_process_product_meta as parameter.
 * return void
 */
function wcmmq_save_field_data( $post_id ){
    
    $_wcmmq_min_quantity = isset( $_POST[WC_MMQ_PREFIX. 'min_quantity'] ) && is_numeric($_POST[WC_MMQ_PREFIX . 'min_quantity']) ? $_POST[WC_MMQ_PREFIX . 'min_quantity'] : false;
    $_wcmmq_default_quantity = isset( $_POST[WC_MMQ_PREFIX . 'default_quantity'] ) && is_numeric($_POST[WC_MMQ_PREFIX . 'default_quantity']) ? $_POST[WC_MMQ_PREFIX . 'default_quantity'] : false;
    $_wcmmq_max_quantity = isset( $_POST[WC_MMQ_PREFIX . 'max_quantity'] ) && is_numeric($_POST[WC_MMQ_PREFIX . 'max_quantity']) ? $_POST[WC_MMQ_PREFIX . 'max_quantity'] : false;
    $_wcmmq_product_step = isset( $_POST[WC_MMQ_PREFIX . 'product_step'] ) && is_numeric($_POST[WC_MMQ_PREFIX . 'product_step']) ? $_POST[WC_MMQ_PREFIX . 'product_step'] : false;
    if($_wcmmq_min_quantity && $_wcmmq_max_quantity && $_wcmmq_min_quantity > $_wcmmq_max_quantity){
        $_wcmmq_max_quantity = $_wcmmq_min_quantity + 5;
    }
    
    if( $_wcmmq_max_quantity ){
        $_wcmmq_default_quantity = $_wcmmq_default_quantity >= $_wcmmq_min_quantity && $_wcmmq_default_quantity <= $_wcmmq_max_quantity ? $_wcmmq_default_quantity : false;
    }else{
        $_wcmmq_default_quantity = $_wcmmq_default_quantity >= $_wcmmq_min_quantity ? $_wcmmq_default_quantity : false;
    }
    
    
    //Updating Here
    update_post_meta( $post_id, WC_MMQ_PREFIX . 'min_quantity', esc_attr( $_wcmmq_min_quantity ) ); 
    update_post_meta( $post_id, WC_MMQ_PREFIX . 'default_quantity', esc_attr( $_wcmmq_default_quantity ) ); 
    update_post_meta( $post_id, WC_MMQ_PREFIX . 'max_quantity', esc_attr( $_wcmmq_max_quantity ) ); 
    update_post_meta( $post_id, WC_MMQ_PREFIX . 'product_step', esc_attr( $_wcmmq_product_step ) ); 
}
add_action( 'woocommerce_process_product_meta', 'wcmmq_save_field_data' );



