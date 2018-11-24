<?php

//Cart Validation
function wcmmq_min_max_valitaion($bool,$product_id,$qantity){
    $min_quantity = get_post_meta($product_id, '_wcmmq_min_quantity', true);
    $max_quantity = get_post_meta($product_id, '_wcmmq_max_quantity', true);
    
    if( $qantity <= $max_quantity && $qantity >= $min_quantity  ){
        return true;
    }elseif( $qantity < $min_quantity ){
        wc_add_notice( __( "Minimum quantity should " . $min_quantity ), 'notice' );
        return;
    }elseif( $qantity < $max_quantity ){
        wc_add_notice( __( "Maximum quantity should " . $min_quantity ), 'notice' );
        return;
    }else{
        return true;
    }
}
add_filter('woocommerce_add_to_cart_validation', 'wcmmq_min_max_valitaion', 10, 3);


/**
 * for Min Qantity
 * 
 * @return void
 */
function wcmmq_set_min_for_single(){
    $post_id = get_the_ID();
    $min_quantity = get_post_meta($post_id, '_wcmmq_min_quantity', true);
    if( ( !empty( $min_quantity ) || !$min_quantity ) && is_numeric($min_quantity) ){
       return $min_quantity; 
    }
    return 1;
}

add_action('woocommerce_before_add_to_cart_quantity', function() {
    add_filter('woocommerce_quantity_input_min','wcmmq_set_min_for_single');
});
add_filter('woocommerce_quantity_input_step','wcmmq_set_min_for_single');
/**
 * for Min Qantity
 * 
 * @return void
 */
function wcmmq_set_max_for_single(){
    $post_id = get_the_ID();
    $max_quantity = get_post_meta($post_id, '_wcmmq_max_quantity', true);
    if( ( !empty( $max_quantity ) || !$max_quantity ) && is_numeric($max_quantity) ){
       return $max_quantity; 
    }
    return;
}

add_action('woocommerce_before_add_to_cart_quantity', function() {
    add_filter('woocommerce_quantity_input_max','wcmmq_set_max_for_single');
});



/**
 * 
 * @param type $button
 * @param type $product
 * @param type $args
 * @return type
 */
function wc_mmq_set_min_qt_in_shop_loop($button,$product,$args){
    $post_id = get_the_ID();
    $min_quantity = get_post_meta($post_id, '_wcmmq_min_quantity', true);
    if( ( !empty( $min_quantity ) || !$min_quantity ) && is_numeric($min_quantity) ){
       $args['quantity'] = $min_quantity; 
    }
    return sprintf( '<a href="%s" title="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
                esc_attr("Minimum quantiy is {$args['quantity']}"),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $product->add_to_cart_text() )
	);
}
add_action('woocommerce_before_shop_loop', function() {
    add_filter('woocommerce_loop_add_to_cart_link','wc_mmq_set_min_qt_in_shop_loop',10,3);
});

/**
 * 

function wc_mmq_add_js_file(){
    //Custom CSS Style for Woo Product Table's Table (Universal-for all table) and (template-for defien-table)
    wp_enqueue_script( 'wc_mmq', WC_MMQ_BASE_URL . '/includes/wc_increament_qt.js', __FILE__, 1.0, true );
    
}
add_action('wp_enqueue_scripts','wc_mmq_add_js_file',99);
 */










