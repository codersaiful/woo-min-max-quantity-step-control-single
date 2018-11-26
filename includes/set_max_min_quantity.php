<?php
/*
// Removes the WooCommerce filter, that is validating the quantity to be an int
remove_filter('woocommerce_stock_amount', 'intval');
 
// Add a filter, that validates the quantity to be a float
add_filter('woocommerce_stock_amount', 'floatval');
 
// Add unit price fix when showing the unit price on processed orders
add_filter('woocommerce_order_amount_item_total', 'unit_price_fix', 10, 5);
function unit_price_fix($price, $order, $item, $inc_tax = false, $round = true) {
    $qty = (!empty($item['qty']) && $item['qty'] != 0) ? $item['qty'] : 1;
    if($inc_tax) {
        $price = ($item['line_total'] + $item['line_tax']) / $qty;
    } else {
        $price = $item['line_total'] / $qty;
    }
    $price = $round ? round( $price, 2 ) : $price;
    return $price;
}
*/
//Cart Validation
function wcmmq_min_max_valitaion($bool,$product_id,$quantity){
    $min_quantity = get_post_meta($product_id, '_wcmmq_min_quantity', true);
    $max_quantity = get_post_meta($product_id, '_wcmmq_max_quantity', true);
    //var_dump($max_quantity);exit;
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ::getOption( '_wcmmq_min_quantity' );
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ::getOption( '_wcmmq_max_quantity' );
    
    
    if( $quantity <= $max_quantity && $quantity >= $min_quantity  ){
        return true;
    }elseif( $quantity < $min_quantity ){
        var_dump($quantity);exit;
        wc_add_notice( __( "Minimum quantity should " . $min_quantity , 'wcmmq' ), 'error' );
        return;
    }elseif( $quantity > $max_quantity ){
        wc_add_notice( __( "Maximum quantity should " . $max_quantity, 'wcmmq' ), 'error' );
        return;
    }else{
        return true;
    }
}
add_filter('woocommerce_add_to_cart_validation', 'wcmmq_min_max_valitaion', 10, 3); //When add to cart

function wcmmq_update_cart_validation( $true, $cart_item_key, $values, $quantity ) { 
    $product_id = $values['product_id'];
    
    $min_quantity = get_post_meta($product_id, '_wcmmq_min_quantity', true);
    $max_quantity = get_post_meta($product_id, '_wcmmq_max_quantity', true);
    //var_dump($max_quantity);exit;
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ::getOption( '_wcmmq_min_quantity' );
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ::getOption( '_wcmmq_max_quantity' );
    
     //wc_add_notice( __( "QT " . $min_quantity, 'wcmmq' ), 'notice' );
    
    if( $quantity <= $max_quantity && $quantity >= $min_quantity ){
        return true;
    }elseif( $quantity > $max_quantity ){
        wc_add_notice( __( "Maximum quantity should " . $max_quantity . ' of ' . get_the_title( $product_id ), 'wcmmq' ), 'error' );
        return;
    }elseif( $quantity < $min_quantity ){
        wc_add_notice( __( "Minimum quantity should " . $min_quantity . ' of ' . get_the_title( $product_id ) , 'wcmmq' ), 'error' );
        return;
    }else{
        return true;
    }
}; 
add_filter('woocommerce_update_cart_validation', 'wcmmq_update_cart_validation', 10, 4); //When Update cart


/**
 * for Min Qantity
 * 
 * @return void
 */
function wcmmq_set_min_for_single(){
    $product_id = get_the_ID();
    $min_quantity = get_post_meta($product_id, '_wcmmq_min_quantity', true);
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ::getOption( '_wcmmq_min_quantity' ); //Regenerate from Default
    if( ( !empty( $min_quantity ) || !$min_quantity ) && is_numeric($min_quantity) ){
       return $min_quantity; 
    }
    return 1;
}

add_action('woocommerce_before_add_to_cart_quantity', function() {
    //add_filter('woocommerce_quantity_input_min','wcmmq_set_min_for_single');
});
add_filter('woocommerce_quantity_input_min','wcmmq_set_min_for_single');


/**
 * for Step
 * 
 * @return void
 */
function wcmmq_set_step_for_single(){
    $product_id = get_the_ID();
    $step_quantity = get_post_meta($product_id, '_wcmmq_product_step', true);
    $step_quantity = !empty( $step_quantity ) ? $step_quantity : WC_MMQ::getOption( '_wcmmq_product_step' );
    if( ( !empty( $step_quantity ) || !$step_quantity ) && is_numeric($step_quantity) ){
       return $step_quantity; 
    }
    return 1;
}
add_action('woocommerce_before_add_to_cart_quantity', function() {
    //add_filter('woocommerce_quantity_input_step','wcmmq_set_step_for_single');
});
add_filter('woocommerce_quantity_input_step','wcmmq_set_step_for_single');

//Testing for cart page
function wcmmq_quantity_input_args_for_cart($args, $product){
    if(is_cart() ){
    $product_id = $product->get_id();
    
    $min_quantity = get_post_meta($product_id, '_wcmmq_min_quantity', true);
    $max_quantity = get_post_meta($product_id, '_wcmmq_max_quantity', true);
    $step_quantity = get_post_meta($product_id, '_wcmmq_product_step', true);
    //If not available in single product, than come from default
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ::getOption( '_wcmmq_min_quantity' );
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ::getOption( '_wcmmq_max_quantity' );
    $step_quantity = !empty( $step_quantity ) ? $step_quantity : WC_MMQ::getOption( '_wcmmq_product_step' );

    $args['max_value'] = $max_quantity; // Max quantity (default = -1)
    $args['min_value'] = $min_quantity; // Min quantity (default = 0)
    $args['step'] = $step_quantity; // Increment/decrement by this value (default = 1)

    }
    return $args;
}
add_filter('woocommerce_quantity_input_args','wcmmq_quantity_input_args_for_cart',10,2);


/**
 * for Min Qantity
 * 
 * @return void
 */
function wcmmq_set_max_for_single(){
    $product_id = get_the_ID();
    $max_quantity = get_post_meta($product_id, '_wcmmq_max_quantity', true);
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ::getOption( '_wcmmq_max_quantity' );
    if( ( !empty( $max_quantity ) || !$max_quantity ) && is_numeric($max_quantity) ){
       return $max_quantity; 
    }
    return;
}

add_action('woocommerce_before_add_to_cart_quantity', function() {
    //add_filter('woocommerce_quantity_input_max','wcmmq_set_max_for_single');
});
add_filter('woocommerce_quantity_input_max','wcmmq_set_max_for_single');


/**
 * 
 * @param type $button
 * @param type $product
 * @param type $args
 * @return type
 */
function wcmmq_set_min_qt_in_shop_loop($button = false,$product = false,$args = false){
    if( $button && $product && $args ):
    $product_id = get_the_ID();
    $min_quantity = get_post_meta($product_id, '_wcmmq_min_quantity', true);
    $max_quantity = get_post_meta($product_id, '_wcmmq_max_quantity', true);
    $step_quantity = get_post_meta($product_id, '_wcmmq_product_step', true);
    //If not available in single product, than come from default
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ::getOption( '_wcmmq_min_quantity' );
    $max_quantity = !empty( $max_quantity ) ? $max_quantity : WC_MMQ::getOption( '_wcmmq_max_quantity' );
    $step_quantity = !empty( $step_quantity ) ? $step_quantity : WC_MMQ::getOption( '_wcmmq_product_step' );
    
    
    if( ( !empty( $min_quantity ) || !$min_quantity ) && is_numeric($min_quantity) ){
        $args['quantity'] = $min_quantity; 
        $args['max_value'] = $max_quantity;
         $args['min_value'] = $min_quantity;
         $args['step'] = $step_quantity;
    }
    return sprintf( '<a href="%s" title="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
                esc_attr("Minimum quantiy is {$args['quantity']}"),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $product->add_to_cart_text() )
	);
    endif;
}
function wcmmq_add_filter_for_shop_n_related_loop(){
    add_filter('woocommerce_loop_add_to_cart_link','wcmmq_set_min_qt_in_shop_loop',10,3);
}
add_action('woocommerce_before_shop_loop','wcmmq_add_filter_for_shop_n_related_loop' );
add_action('woocommerce_after_single_product_summary','wcmmq_add_filter_for_shop_n_related_loop' );

//add_filter('woocommerce_loop_add_to_cart_link','wcmmq_set_min_qt_in_shop_loop',10,3);

/*
add_action('woocommerce_before_shop_loop', function() {
    add_filter('woocommerce_loop_add_to_cart_link','wcmmq_set_min_qt_in_shop_loop',10,3);
});
*/


/**
 * 

function wc_mmq_add_js_file(){
    //Custom CSS Style for Woo Product Table's Table (Universal-for all table) and (template-for defien-table)
    wp_enqueue_script( 'wc_mmq', WC_MMQ_BASE_URL . '/includes/wc_increament_qt.js', __FILE__, 1.0, true );
    
}
add_action('wp_enqueue_scripts','wc_mmq_add_js_file',99);
 */









/**
Array
(
    [37693cfc748049e45d87b8c7d8b9aacd] => Array
        (
            [key] => 37693cfc748049e45d87b8c7d8b9aacd
            [product_id] => 23
            [variation_id] => 0
            [variation] => Array
                (
                )

            [quantity] => 60
            [data_hash] => b5c1d5ca8bae6d4896cf1807cdf763f0
            [line_tax_data] => Array
                (
                    [subtotal] => Array
                        (
                        )

                    [total] => Array
                        (
                        )

                )

            [line_subtotal] => 120
            [line_subtotal_tax] => 0
            [line_total] => 120
            [line_tax] => 0
            [data] => WC_Product_Simple Object
                (
                    [object_type:protected] => product
                    [post_type:protected] => product
                    [cache_group:protected] => products
                    [data:protected] => Array
                        (
                            [name] => Single
                            [slug] => single
                            [date_created] => WC_DateTime Object
                                (
                                    [utc_offset:protected] => 0
                                    [date] => 2018-11-18 05:09:47.000000
                                    [timezone_type] => 1
                                    [timezone] => +00:00
                                )

                            [date_modified] => WC_DateTime Object
                                (
                                    [utc_offset:protected] => 0
                                    [date] => 2018-11-25 10:52:46.000000
                                    [timezone_type] => 1
                                    [timezone] => +00:00
                                )

                            [status] => publish
                            [featured] => 
                            [catalog_visibility] => visible
                            [description] => Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sagittis orci ac odio dictum tincidunt. Donec ut metus leo. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed luctus, dui eu sagittis sodales, nulla nibh sagittis augue, vel porttitor diam enim non metus. Vestibulum aliquam augue neque. Phasellus tincidunt odio eget ullamcorper efficitur. Cras placerat ut turpis pellentesque vulputate. Nam sed consequat tortor. Curabitur finibus sapien dolor. Ut eleifend tellus nec erat pulvinar dignissim. Nam non arcu purus. Vivamus et massa massa.
                            [short_description] => This is a simple, virtual product.
                            [sku] => woo-single
                            [price] => 2
                            [regular_price] => 3
                            [sale_price] => 2
                            [date_on_sale_from] => 
                            [date_on_sale_to] => 
                            [total_sales] => 0
                            [tax_status] => taxable
                            [tax_class] => 
                            [manage_stock] => 1
                            [stock_quantity] => 0
                            [stock_status] => onbackorder
                            [backorders] => yes
                            [low_stock_amount] => 0
                            [sold_individually] => 
                            [weight] => 
                            [length] => 
                            [width] => 
                            [height] => 
                            [upsell_ids] => Array
                                (
                                )

                            [cross_sell_ids] => Array
                                (
                                )

                            [parent_id] => 0
                            [reviews_allowed] => 1
                            [purchase_note] => 
                            [attributes] => Array
                                (
                                )

                            [default_attributes] => Array
                                (
                                )

                            [menu_order] => 0
                            [virtual] => 1
                            [downloadable] => 
                            [category_ids] => Array
                                (
                                    [0] => 20
                                )

                            [tag_ids] => Array
                                (
                                )

                            [shipping_class_id] => 0
                            [downloads] => Array
                                (
                                    [5470a7ef-48ee-4bd9-b2f6-b9a0853b37e3] => WC_Product_Download Object
                                        (
                                            [data:protected] => Array
                                                (
                                                    [id] => 5470a7ef-48ee-4bd9-b2f6-b9a0853b37e3
                                                    [name] => Single
                                                    [file] => https://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2017/08/single.jpg
                                                )

                                        )

                                )

                            [image_id] => 52
                            [gallery_image_ids] => Array
                                (
                                )

                            [download_limit] => 1
                            [download_expiry] => 1
                            [rating_counts] => Array
                                (
                                )

                            [average_rating] => 0
                            [review_count] => 0
                        )

                    [supports:protected] => Array
                        (
                            [0] => ajax_add_to_cart
                        )

                    [id:protected] => 23
                    [changes:protected] => Array
                        (
                        )

                    [object_read:protected] => 1
                    [extra_data:protected] => Array
                        (
                        )

                    [default_data:protected] => Array
                        (
                            [name] => 
                            [slug] => 
                            [date_created] => 
                            [date_modified] => 
                            [status] => 
                            [featured] => 
                            [catalog_visibility] => visible
                            [description] => 
                            [short_description] => 
                            [sku] => 
                            [price] => 
                            [regular_price] => 
                            [sale_price] => 
                            [date_on_sale_from] => 
                            [date_on_sale_to] => 
                            [total_sales] => 0
                            [tax_status] => taxable
                            [tax_class] => 
                            [manage_stock] => 
                            [stock_quantity] => 
                            [stock_status] => instock
                            [backorders] => no
                            [low_stock_amount] => 
                            [sold_individually] => 
                            [weight] => 
                            [length] => 
                            [width] => 
                            [height] => 
                            [upsell_ids] => Array
                                (
                                )

                            [cross_sell_ids] => Array
                                (
                                )

                            [parent_id] => 0
                            [reviews_allowed] => 1
                            [purchase_note] => 
                            [attributes] => Array
                                (
                                )

                            [default_attributes] => Array
                                (
                                )

                            [menu_order] => 0
                            [virtual] => 
                            [downloadable] => 
                            [category_ids] => Array
                                (
                                )

                            [tag_ids] => Array
                                (
                                )

                            [shipping_class_id] => 0
                            [downloads] => Array
                                (
                                )

                            [image_id] => 
                            [gallery_image_ids] => Array
                                (
                                )

                            [download_limit] => -1
                            [download_expiry] => -1
                            [rating_counts] => Array
                                (
                                )

                            [average_rating] => 0
                            [review_count] => 0
                        )

                    [data_store:protected] => WC_Data_Store Object
                        (
                            [instance:WC_Data_Store:private] => WC_Product_Data_Store_CPT Object
                                (
                                    [internal_meta_keys:protected] => Array
                                        (
                                            [0] => _visibility
                                            [1] => _sku
                                            [2] => _price
                                            [3] => _regular_price
                                            [4] => _sale_price
                                            [5] => _sale_price_dates_from
                                            [6] => _sale_price_dates_to
                                            [7] => total_sales
                                            [8] => _tax_status
                                            [9] => _tax_class
                                            [10] => _manage_stock
                                            [11] => _stock
                                            [12] => _stock_status
                                            [13] => _backorders
                                            [14] => _low_stock_amount
                                            [15] => _sold_individually
                                            [16] => _weight
                                            [17] => _length
                                            [18] => _width
                                            [19] => _height
                                            [20] => _upsell_ids
                                            [21] => _crosssell_ids
                                            [22] => _purchase_note
                                            [23] => _default_attributes
                                            [24] => _product_attributes
                                            [25] => _virtual
                                            [26] => _downloadable
                                            [27] => _download_limit
                                            [28] => _download_expiry
                                            [29] => _featured
                                            [30] => _downloadable_files
                                            [31] => _wc_rating_count
                                            [32] => _wc_average_rating
                                            [33] => _wc_review_count
                                            [34] => _variation_description
                                            [35] => _thumbnail_id
                                            [36] => _file_paths
                                            [37] => _product_image_gallery
                                            [38] => _product_version
                                            [39] => _wp_old_slug
                                            [40] => _edit_last
                                            [41] => _edit_lock
                                        )

                                    [extra_data_saved:protected] => 
                                    [updated_props:protected] => Array
                                        (
                                        )

                                    [meta_type:protected] => post
                                    [object_id_field_for_meta:protected] => 
                                )

                            [stores:WC_Data_Store:private] => Array
                                (
                                    [coupon] => WC_Coupon_Data_Store_CPT
                                    [customer] => WC_Customer_Data_Store
                                    [customer-download] => WC_Customer_Download_Data_Store
                                    [customer-download-log] => WC_Customer_Download_Log_Data_Store
                                    [customer-session] => WC_Customer_Data_Store_Session
                                    [order] => WC_Order_Data_Store_CPT
                                    [order-refund] => WC_Order_Refund_Data_Store_CPT
                                    [order-item] => WC_Order_Item_Data_Store
                                    [order-item-coupon] => WC_Order_Item_Coupon_Data_Store
                                    [order-item-fee] => WC_Order_Item_Fee_Data_Store
                                    [order-item-product] => WC_Order_Item_Product_Data_Store
                                    [order-item-shipping] => WC_Order_Item_Shipping_Data_Store
                                    [order-item-tax] => WC_Order_Item_Tax_Data_Store
                                    [payment-token] => WC_Payment_Token_Data_Store
                                    [product] => WC_Product_Data_Store_CPT
                                    [product-grouped] => WC_Product_Grouped_Data_Store_CPT
                                    [product-variable] => WC_Product_Variable_Data_Store_CPT
                                    [product-variation] => WC_Product_Variation_Data_Store_CPT
                                    [shipping-zone] => WC_Shipping_Zone_Data_Store
                                    [webhook] => WC_Webhook_Data_Store
                                )

                            [current_class_name:WC_Data_Store:private] => WC_Product_Data_Store_CPT
                            [object_type:WC_Data_Store:private] => product-simple
                        )

                    [meta_data:protected] => 
                )

        )

)
 */
