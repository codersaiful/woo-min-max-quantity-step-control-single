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
        'label'     =>  __( 'Minimum Quantity', 'wcmmq' ),
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> __( 'Enter Minimum Quantity for this Product', 'wcmmq' ),
        'data_type' => 'decimal'
    );
    $default_qty = apply_filters( 'wcmmq_default_qty_option', false, get_the_ID() );
    if( $default_qty ){
        $args[] = array(
            'id'        =>  WC_MMQ_PREFIX. 'default_quantity',
            'name'        =>  WC_MMQ_PREFIX. 'default_quantity',
            'label'     =>  __( 'Default Quantity (Optional)', 'wcmmq' ),
            'class'     =>  'wcmmq_input',
            'type'      =>  'text',
            'desc_tip'  =>  true,
            'description'=> __( 'It is an optional Number, If do not set, Product default quantity will come from Minimum Quantity', 'wcmmq' ),
            'data_type' => 'decimal'
        );
    }    
    $args[] = array(
        'id'        =>  WC_MMQ_PREFIX. 'max_quantity',
        'name'        =>  WC_MMQ_PREFIX. 'max_quantity',
        'label'     =>  __( 'Maximum Quantity', 'wcmmq' ),
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> __( 'Enter Maximum Quantity for this Product', 'wcmmq' ),
        'data_type' => 'decimal'
    );
    
    
    
    $args[] = array(
        'id'        =>  WC_MMQ_PREFIX. 'product_step',
        'name'        =>  WC_MMQ_PREFIX. 'product_step',
        'label'     =>  __( 'Quantity Step', 'wcmmq' ),
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> __( 'Enter quantity Step', 'wcmmq' ),
        'data_type' => 'decimal'
    );

    /**
     * @Hook wcmmq_field_args_in_panel 
     * Sample use of this hook:
add_filter('wcmmq_field_args_in_panel' , function($args){
    
    $args = array_map(function($my_arr){
        array_pop($my_arr);
        return $my_arr;
    },$args);  
    return $args;
});
     * 
     */
    $args = apply_filters('wcmmq_field_args_in_panel', $args);

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
    
    $min_quantity = $_POST[WC_MMQ_PREFIX. 'min_quantity'] ?? false;// isset( $_POST[WC_MMQ_PREFIX. 'min_quantity'] ) && is_numeric($_POST[WC_MMQ_PREFIX . 'min_quantity']) ? $_POST[WC_MMQ_PREFIX . 'min_quantity'] : false;
    $default_quantity = $_POST[WC_MMQ_PREFIX . 'default_quantity'] ?? false;// isset( $_POST[WC_MMQ_PREFIX . 'default_quantity'] ) && is_numeric($_POST[WC_MMQ_PREFIX . 'default_quantity']) ? $_POST[WC_MMQ_PREFIX . 'default_quantity'] : false;
    $max_quantity = $_POST[WC_MMQ_PREFIX . 'max_quantity'] ?? false;//isset( $_POST[WC_MMQ_PREFIX . 'max_quantity'] ) && is_numeric($_POST[WC_MMQ_PREFIX . 'max_quantity']) ? $_POST[WC_MMQ_PREFIX . 'max_quantity'] : false;
    $product_step = $_POST[WC_MMQ_PREFIX . 'product_step'] ?? false;//isset( $_POST[WC_MMQ_PREFIX . 'product_step'] ) && is_numeric($_POST[WC_MMQ_PREFIX . 'product_step']) ? $_POST[WC_MMQ_PREFIX . 'product_step'] : false;
    
    $min_quantity = wc_format_decimal( $min_quantity );
    $default_quantity = wc_format_decimal( $default_quantity );
    $max_quantity = wc_format_decimal( $max_quantity );
    $product_step = wc_format_decimal( $product_step );
    
    if($min_quantity && $max_quantity && $min_quantity > $max_quantity){
        $max_quantity = $min_quantity + 5;
    }
    
    if( $max_quantity ){
        $default_quantity = $default_quantity >= $min_quantity && $default_quantity <= $max_quantity ? $default_quantity : false;
    }else{
        $default_quantity = $default_quantity >= $min_quantity ? $default_quantity : false;
    }
    
    
    //Updating Here
    update_post_meta( $post_id, WC_MMQ_PREFIX . 'min_quantity', esc_attr( $min_quantity ) ); 
    $default_qty = apply_filters( 'wcmmq_default_qty_option', false, get_the_ID() );
    if( $default_qty ){
        update_post_meta( $post_id, WC_MMQ_PREFIX . 'default_quantity', esc_attr( $default_quantity ) ); 
    }
    update_post_meta( $post_id, WC_MMQ_PREFIX . 'max_quantity', esc_attr( $max_quantity ) );
    update_post_meta( $post_id, WC_MMQ_PREFIX . 'product_step', esc_attr( $product_step ) ); 
}
add_action( 'woocommerce_process_product_meta', 'wcmmq_save_field_data' );



