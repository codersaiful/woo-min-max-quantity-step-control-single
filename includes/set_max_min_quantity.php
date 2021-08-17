<?php

/**
 * Getting current quantity in cart of current product. I mean: we will check it by product ID.
 * 
 * @global type $woocommerce We have used $woocommerce variable.
 * @param int $product_id Need Product_ID for check current quantity in cart
 * @return int
 */
function wcmmq_s_check_quantity_in_cart( $product_id, $variation_id = 0 ) {
    global $woocommerce;
    foreach( $woocommerce->cart->get_cart() as $key => $value ) {
        if( $product_id == $value['product_id'] && $variation_id == $value['variation_id'] ) {
 	        return $value['quantity'];
        }
    }
    return 0;
}

/**
 * Qty Validation based on Step
 * Added on Version: 1.8.3
 * 
 * @param type $quantity
 * @param type float
 * @param type $step
 * @return boolean True for pass valid, false for Fail
 */
function wcmmq_qty_validation_by_step_modulous( $modulous, $product_id, $quantity, $min_quantity, $step_quantity ){

    if(!is_numeric( $quantity ) || !is_numeric( $step_quantity ))return false;
    $consnt_value = 1000000;
    $quantity_int = intval( $quantity * $consnt_value );
    $min_quantity_int = intval( $min_quantity * $consnt_value );
    $step_int = intval( $step_quantity * $consnt_value );
    $final_qty = intval( $quantity_int - $min_quantity_int );
    $module = $final_qty % $step_int;
    $modulous = false;
    if($module == 0) {
        $modulous = true;
    }
    
    $should_min = $quantity > $min_quantity ? ($quantity - ($module/$consnt_value)) : $min_quantity;
    $should_next = $should_min + $step_quantity;
    $modulous = apply_filters( 'wcmmq_last_step_checker_filter', $modulous, $product_id, $quantity, $min_quantity, $step_quantity );
    $specific_msge = false;
    wcmmq_step_error_message( $modulous, $specific_msge, $should_min, $should_next );
    return $modulous;
}

if( ! function_exists( 'wcmmq_step_error_message' ) ){
    function wcmmq_step_error_message( $bool = true, $specific_msge, $should_min, $should_next ){
        if( $bool ) return true;
        
        $message = sprintf( WC_MMQ_S::getOption( '_wcmmq_step_error_valiation' ) . $specific_msge, $should_min, $should_next );
        wc_add_notice( $message, 'error' );
    }
}
add_filter( 'wcmmq_modulous_validation', 'wcmmq_qty_validation_by_step_modulous', 10, 5 );
add_filter( 'wcmmq_last_step_checker_filter', 'wcmmq_last_step_checker', 10, 5 );
if( ! function_exists( 'wcmmq_last_step_checker' ) ){
    function wcmmq_last_step_checker( $modulous, $product_id, $quantity, $min_quantity, $step_quantity ){

        //Only if true
        if( $modulous ) return $modulous;
        $product = wc_get_product( $product_id );
        //Only if stock manage
        if( ! $product->managing_stock() ) return $modulous;

        $stock_qty = $product->get_stock_quantity(); // 17
        //
        $last_step = $stock_qty % $step_quantity;
        $last_v_stock = $stock_qty - $last_step;
        if( $quantity < $last_v_stock) return $modulous;
        
        if( $quantity == ( $last_step + $last_v_stock ) ) return true;
        
        
        return $modulous;
    
    }
}

/**
 * Setting minimum and maximum quantity validation when product adding to cart. 
 * We also used current quantity [$current_qty_inCart] of cart for checking limitation.
 * 
 * @param type bool
 * @param type int post Id
 * @param type int Quantity when will add to cart
 * @param type int for Variable product
 * @param type array Variations as Array
 * @return boolean True or false
 * 
 * @link https://docs.woocommerce.com/wc-apidocs/source-class-WC_AJAX.html#365 Details
 * @since 1.0
 */
function wcmmq_s_min_max_valitaion( $bool, $product_id, $quantity, $variation_id = 0, $variations = false ){ //Right two parameters added
    $product = wc_get_product( $product_id );
    // if product is sold individually then we can immediately exit here
    if( $product->is_sold_individually() ) return true;
    
    $min_quantity = get_post_meta( $product_id, '_wcmmq_s_min_quantity', true );
    $max_quantity = get_post_meta( $product_id, '_wcmmq_s_max_quantity', true );
    $step_quantity = get_post_meta( $product_id, '_wcmmq_s_product_step', true );
    
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ_S::getOption( '_wcmmq_s_min_quantity' );
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ_S::getOption( '_wcmmq_s_max_quantity' );
    $step_quantity = !empty( $step_quantity ) ? $step_quantity : WC_MMQ_S::getOption( '_wcmmq_s_product_step' );

    /**
     * Getting current Quantity from Cart
     */
    $current_qty_inCart = wcmmq_s_check_quantity_in_cart( $product_id, $variation_id );
    $total_quantity = $current_qty_inCart + $quantity;
    $product_name = get_the_title( $product_id );
    
    // $modulous = wcmmq_qty_validation_by_step_modulous( $modulous, $product_id, $quantity, $min_quantity, $step_quantity );
    $modulous = apply_filters( 'wcmmq_modulous_validation', false, $product_id, $quantity, $min_quantity, $step_quantity );
    if( $total_quantity <= $max_quantity && $total_quantity >= $min_quantity && $modulous  ){
        return true;
    }elseif( $min_quantity && $total_quantity < $min_quantity ){
        $message = sprintf( WC_MMQ_S::getOption( '_wcmmq_s_msg_min_limit' ), $min_quantity, $product_name ); // __( 'Minimum quantity should %s of "%s"', 'wcmmq' ) //Control from main file
        wc_add_notice( $message, 'error' );
        return;
    }elseif( $max_quantity && $total_quantity > $max_quantity ){
        $message = false;
        if( $current_qty_inCart > 0 ){
            $message .= sprintf( WC_MMQ_S::getOption( '_wcmmq_s_msg_max_limit_with_already' ), $current_qty_inCart, $product_name );
            $message .= " <br>";
        }
        $message .= sprintf( WC_MMQ_S::getOption( '_wcmmq_s_msg_max_limit' ), $max_quantity, $product_name ); // __( 'Minimum quantity should %s of "%s"', 'wcmmq' ) //Control from main file
        wc_add_notice( $message, 'error' );
        return false;
    }elseif( !$modulous ){
        // $message = "Number should be ...";
        // wc_add_notice( $message, 'error' );
        return false;
    }else{
        return true;
    }
}
add_filter('woocommerce_add_to_cart_validation', 'wcmmq_s_min_max_valitaion', 10, 5); //When add to cart

/**
 * Validation when you will update cart page of WooCommerce. Actually Minimum and maximum as well as step should be fixed
 * on cart page. So that we have used this function by using filter 'woocommerce_update_cart_validation'
 * 
 * @param type $true
 * @param type $cart_item_key
 * @param type $values
 * @param type $quantity
 * @return boolean
 * 
 * @link https://docs.woocommerce.com/wc-apidocs/source-class-WC_Form_Handler.html#568 Details
 * @since 1.0
 */
function wcmmq_s_update_cart_validation( $true, $cart_item_key, $values, $quantity ) { 
    if( ! isset( $values['product_id'] ) ){
        return $true;
    }
    $product_id = $values['product_id']; //Already checked
    
    $min_quantity = get_post_meta($product_id, '_wcmmq_s_min_quantity', true);
    $max_quantity = get_post_meta($product_id, '_wcmmq_s_max_quantity', true);
    //var_dump($max_quantity);exit;
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ_S::getOption( '_wcmmq_s_min_quantity' );
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ_S::getOption( '_wcmmq_s_max_quantity' );
    
    $product_name = get_the_title( $product_id );
     //wc_add_notice( __( "QT " . $min_quantity, 'wcmmq' ), 'notice' );
    
    if( (!empty($max_quantity) && $max_quantity > 0 && $quantity <= $max_quantity) && $quantity >= $min_quantity ){
        return true;
    }elseif(empty($max_quantity) && $quantity >= $min_quantity){
        return true;
    }elseif(!empty($max_quantity) && $max_quantity > 0 && $quantity > $max_quantity ){
        $message = sprintf( WC_MMQ_S::getOption( '_wcmmq_s_msg_max_limit' ), $max_quantity, $product_name ); // __( 'Minimum quantity should %s of "%s"', 'wcmmq' ) //Control from main file
        wc_add_notice( $message, 'error' );
        return;
    }elseif( $quantity < $min_quantity ){
        $message = sprintf( WC_MMQ_S::getOption( '_wcmmq_s_msg_min_limit' ), $min_quantity, $product_name ); // __( 'Minimum quantity should %s of "%s"', 'wcmmq' ) //Control from main file
        wc_add_notice( $message, 'error' );
        return;
    }else{
        return true;
    }
}; 
add_filter('woocommerce_update_cart_validation', 'wcmmq_s_update_cart_validation', 10, 4); //When Update cart

/**
 * Getting quantity arguments for All, 
 * This quantity arguments will work for type product ande page.
 * Single page of all type product such: simple,variable
 * Cart page , checkout page
 * 
 * @param type $args Quantity arguments
 * @param type $product Product's Object to get ID of product
 * @return type Array
 * 
 * @since 1.0
 * @link https://docs.woocommerce.com/wc-apidocs/source-function-woocommerce_quantity_input.html#1234 Details of filter 'woocommerce_quantity_input_args'
 */
function wcmmq_s_quantity_input_args($args, $product){
    
    // return default if product is sold individually
    if( $product->is_sold_individually() ) return $args;

    $product_id = get_the_ID();
    if( is_cart() ){
        if( $product->get_type() == 'variation' ){
            $product_id = $product->get_parent_id();
        }else{
            $product_id = $product->get_id();
        }
    }
    $min_quantity = get_post_meta($product_id, '_wcmmq_s_min_quantity', true);
    $max_quantity = get_post_meta($product_id, '_wcmmq_s_max_quantity', true);
    $step_quantity = get_post_meta($product_id, '_wcmmq_s_product_step', true);
    //If not available in single product, than come from default
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ_S::getOption( '_wcmmq_s_min_quantity' );
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ_S::getOption( '_wcmmq_s_max_quantity' );
    $step_quantity = !empty( $step_quantity ) ? $step_quantity : WC_MMQ_S::getOption( '_wcmmq_s_product_step' );

    // Max quantity (default = -1)
    // simple product
    if( isset( $args['max_value'] ) && $args['max_value'] > -1){
        // stock quantity already set
        $args['max_value']  = $max_quantity && $max_quantity <= $args['max_value']   ? $max_quantity : $args['max_value'];

    }elseif( !empty( $args['max_qty'] ) ){
        // variable product
        // stock quantity already set
        $args['max_qty']    = $max_quantity && $max_quantity <= $args['max_qty']     ? $max_quantity : $args['max_qty'];
    }else{
        $args['max_value'] = $args['max_qty'] = $max_quantity;
    }

    $args['min_value'] = $args['min_qty'] = $min_quantity; // Min quantity (default = 0)
    if( ! is_cart() ){
        $args['input_value'] = $min_quantity; // Min quantity (default = 0)
    }
    $args['step'] = $step_quantity; // Increment/decrement by this value (default = 1)


    return $args;
}
add_filter('woocommerce_quantity_input_args','wcmmq_s_quantity_input_args',10,2);
add_filter('woocommerce_available_variation','wcmmq_s_quantity_input_args',10,2); //For Variable product

/**
 * Set limit on Single product page for Minimum Quantity of Product
 * 
 * @return void
 * @since 1.0
 */
function wcmmq_s_set_min_for_single( $quantity, $product ){
    $min_quantity = get_post_meta( $product->get_id(), '_wcmmq_s_min_quantity', true);
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ_S::getOption( '_wcmmq_s_min_quantity' ); //Regenerate from Default
    if( !$product->is_sold_individually() && ( !empty( $min_quantity ) || !$min_quantity ) && is_numeric($min_quantity) ){
       return $min_quantity; 
    }
    return 1;
}
add_filter('woocommerce_quantity_input_min','wcmmq_s_set_min_for_single', 10, 2 );

/**
 * Setting quantity in Loop of Shop Page
 * Related page and Category And tag Loop Page
 * 
 * @param type $button
 * @param type $product
 * @param type $args
 * @return type
 */
function wcmmq_s_set_min_qt_in_shop_loop($button = false,$product = false,$args = false){

    if( $button && $product && $args ):
    $product_id = get_the_ID();
    $min_quantity = get_post_meta($product_id, '_wcmmq_s_min_quantity', true);
    $max_quantity = get_post_meta($product_id, '_wcmmq_s_max_quantity', true);
    $step_quantity = get_post_meta($product_id, '_wcmmq_s_product_step', true);
    //If not available in single product, than come from default
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ_S::getOption( '_wcmmq_s_min_quantity' );
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ_S::getOption( '_wcmmq_s_max_quantity' );
    $step_quantity = !empty( $step_quantity ) ? $step_quantity : WC_MMQ_S::getOption( '_wcmmq_s_product_step' );
    

    if( ( !empty( $min_quantity ) || !$min_quantity ) && is_numeric($min_quantity) ){
        $args['quantity']   = $min_quantity; 
        $args['max_value']  = $max_quantity;
        $args['min_value']  = $min_quantity;
        $args['step']       = $step_quantity;
    }
    return sprintf( '<a href="%s" title="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
        esc_attr( WC_MMQ_S::getOption( '_wcmmq_s_min_qty_msg_in_loop' ) . " " .$args['quantity'] ), //"Minimum quantiy is {$args['quantity']}"
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $product->add_to_cart_text() )
	);
    endif;
}

/**
 * Adding filter for Shop Page as well as Related product, which normally show in Single product page at bottom section
 * 
 * @link https://docs.woocommerce.com/document/override-loop-template-and-show-quantities-next-to-add-to-cart-buttons/ Details about: Override loop template and show quantities next to add to cart buttons.
 * @since 1.0.14
 */
function wcmmq_s_add_filter_for_shop_n_related_loop(){
    add_filter('woocommerce_loop_add_to_cart_link','wcmmq_s_set_min_qt_in_shop_loop',10,3);
}
add_action('woocommerce_before_shop_loop','wcmmq_s_add_filter_for_shop_n_related_loop' );
add_action('woocommerce_after_single_product_summary','wcmmq_s_add_filter_for_shop_n_related_loop' );