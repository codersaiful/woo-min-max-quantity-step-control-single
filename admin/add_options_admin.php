<?php

/**
 * Min Max & Step panel for each Product's edit page
 * Field adding to Product Page
 * 
 * @since 1.0
 * @link https://docs.woocommerce.com/wc-apidocs/source-function-woocommerce_wp_text_input.html#14-79 Details of woocommerce_wp_text_input() from WooCommerce
 */
function wcmmq_add_field_in_panel(){
    $args = false;
    $args[] = array(
        'id'        =>  '_wcmmq_min_quantity',
        'name'        =>  '_wcmmq_min_quantity',
        'label'     =>  'Min Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Enter Minimum Quantity for this Product',
        'data_type' => 'decimal'
    );
    
    $args[] = array(
        'id'        =>  '_wcmmq_default_quantity',
        'name'        =>  '_wcmmq_default_quantity',
        'label'     =>  'Default Quantity (Optional)',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'It is an optional Number, If do not set, Product default quantity will come from Minimum Quantity',
        'data_type' => 'decimal'
    );
    
    $args[] = array(
        'id'        =>  '_wcmmq_max_quantity',
        'name'        =>  '_wcmmq_max_quantity',
        'label'     =>  'Max Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Enter Maximum Quantity for this Product',
        'data_type' => 'decimal'
    );
    
    $args[] = array(
        'id'        =>  '_wcmmq_product_step',
        'name'        =>  '_wcmmq_product_step',
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
    
    $_wcmmq_min_quantity = isset( $_POST['_wcmmq_min_quantity'] ) && is_numeric($_POST['_wcmmq_min_quantity']) ? $_POST['_wcmmq_min_quantity'] : false;
    $_wcmmq_default_quantity = isset( $_POST['_wcmmq_default_quantity'] ) && is_numeric($_POST['_wcmmq_default_quantity']) ? $_POST['_wcmmq_default_quantity'] : false;
    $_wcmmq_max_quantity = isset( $_POST['_wcmmq_max_quantity'] ) && is_numeric($_POST['_wcmmq_max_quantity']) ? $_POST['_wcmmq_max_quantity'] : false;
    $_wcmmq_product_step = isset( $_POST['_wcmmq_product_step'] ) && is_numeric($_POST['_wcmmq_product_step']) ? $_POST['_wcmmq_product_step'] : false;
    if($_wcmmq_min_quantity && $_wcmmq_max_quantity && $_wcmmq_min_quantity > $_wcmmq_max_quantity){
        $_wcmmq_max_quantity = $_wcmmq_min_quantity + 5;
    }
    
    $_wcmmq_default_quantity = $_wcmmq_default_quantity >= $_wcmmq_min_quantity && $_wcmmq_default_quantity <= $_wcmmq_max_quantity ? $_wcmmq_default_quantity : false;
    
    if( !$_wcmmq_product_step ){
        $_wcmmq_product_step = $_wcmmq_min_quantity;
    }
    
    //Updating Here
    update_post_meta( $post_id, '_wcmmq_min_quantity', esc_attr( $_wcmmq_min_quantity ) ); 
    update_post_meta( $post_id, '_wcmmq_default_quantity', esc_attr( $_wcmmq_default_quantity ) ); 
    update_post_meta( $post_id, '_wcmmq_max_quantity', esc_attr( $_wcmmq_max_quantity ) ); 
    update_post_meta( $post_id, '_wcmmq_product_step', esc_attr( $_wcmmq_product_step ) ); 
}
add_action( 'woocommerce_process_product_meta', 'wcmmq_save_field_data' );


// 1. Add custom field input @ Product Data > Variations > Single Variation
 
add_action( 'woocommerce_variation_options', 'wcmmq_add_custom_field_to_variations', 10, 3 );
 
function wcmmq_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
    //var_dump($loop);
    //var_dump( $loop, $variation_data, $variation );
    $args = false;
    $args[] = array(
        'id'        =>  '_wcmmq_min_quantity[' . $loop . ']',
        //'name'        =>  '_wcmmq_min_quantity',
        'label'     =>  'Min Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Enter Minimum Quantity for this Variation',
        'data_type' => 'decimal',
        'value' => get_post_meta( $variation->ID, '_wcmmq_min_quantity', true ),
    );
    
    $args[] = array(
        'id'        =>  '_wcmmq_default_quantity[' . $loop . ']',
        //'name'        =>  '_wcmmq_default_quantity',
        'label'     =>  'Default Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'It is an optional Number, If do not set, Product default quantity will come from Minimum Quantity',
        'data_type' => 'decimal',
        'value' => get_post_meta( $variation->ID, '_wcmmq_default_quantity', true ),
    );
    
    
    $args[] = array(
        'id'        =>  '_wcmmq_max_quantity[' . $loop . ']',
        //'name'        =>  '_wcmmq_max_quantity',
        'label'     =>  'Max Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Enter Maximum Quantity for this Variation',
        'data_type' => 'decimal',
        'value' => get_post_meta( $variation->ID, '_wcmmq_max_quantity', true ),
    );
    
    $args[] = array(
        'id'        =>  '_wcmmq_product_step[' . $loop . ']',
        //'name'        =>  '_wcmmq_product_step',
        'label'     =>  'Quantity Step',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Enter quantity for this Variation',
        'data_type' => 'decimal',
        'value' => get_post_meta( $variation->ID, '_wcmmq_product_step', true ),
    );
    
    foreach($args as $arg){
        woocommerce_wp_text_input($arg);
    }
    
}
 
// -----------------------------------------
// 2. Save custom field on product variation save
 
add_action( 'woocommerce_save_product_variation', 'wcmmq_save_custom_field_variations', 10, 2 );
 
function wcmmq_save_custom_field_variations( $variation_id, $i ) {
    $args = array(
        '_wcmmq_min_quantity',
        '_wcmmq_default_quantity',
        '_wcmmq_max_quantity',
        '_wcmmq_product_step',
    );
    foreach($args as $arg){
        $custom_field = $_POST[$arg][$i];
        if ( isset( $custom_field ) ) update_post_meta( $variation_id, $arg, esc_attr( $custom_field ) );
    }
}

