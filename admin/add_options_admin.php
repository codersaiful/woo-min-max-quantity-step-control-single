<?php
function wcmmq_add_field_in_advanced(){
    $args = false;
    $args[] = array(
        'id'        =>  '_wcmmq_min_quantity',
        'name'        =>  '_wcmmq_min_quantity',
        'label'     =>  'Min Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'number',
        'desc_tip'  =>  true,
        'description'=> 'Enter Minimum Quantity for this Product'
    );
    
    $args[] = array(
        'id'        =>  '_wcmmq_max_quantity',
        'name'        =>  '_wcmmq_max_quantity',
        'label'     =>  'Max Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'number',
        'desc_tip'  =>  true,
        'description'=> 'Enter Maximum Quantity for this Product'
    );
    
    foreach($args as $arg){
        woocommerce_wp_text_input($arg);
    }
}

add_action('woocommerce_product_options_advanced','wcmmq_add_field_in_advanced');
//add_action('woocommerce_product_options_general_product_data','wcmmq_add_field_in_general');
//add_action('woocommerce_product_options_general_product_data','wcmmq_add_field_in_general');

function wcmmq_save_field_data( $post_id ){
    
    $_wcmmq_min_quantity = isset( $_POST['_wcmmq_min_quantity'] ) && is_numeric($_POST['_wcmmq_min_quantity']) ? $_POST['_wcmmq_min_quantity'] : false;
    $_wcmmq_max_quantity = isset( $_POST['_wcmmq_max_quantity'] ) && is_numeric($_POST['_wcmmq_max_quantity']) ? $_POST['_wcmmq_max_quantity'] : false;
    if($_wcmmq_min_quantity && $_wcmmq_max_quantity && $_wcmmq_min_quantity > $_wcmmq_max_quantity){
        $_wcmmq_max_quantity = $_wcmmq_min_quantity + 5;
    }
    
    //Updating Here
    update_post_meta( $post_id, '_wcmmq_min_quantity', esc_attr( $_wcmmq_min_quantity ) ); 
    update_post_meta( $post_id, '_wcmmq_max_quantity', esc_attr( $_wcmmq_max_quantity ) ); 

    

    /*
    $product = wc_get_product( $post_id );
    $min_quantity = isset( $_POST['wcmmq_min_quantity'] ) ? $_POST['wcmmq_min_quantity'] : 1;
    $product->save_meta_data('wcmmq_min_quantity',$min_quantity);
    $product->save();
    */
}
add_action( 'woocommerce_process_product_meta', 'wcmmq_save_field_data' );
