<?php 

/**
 * Getting term list new/agin generate based on
 * wpml
 * 
 * asole wpml er madhome category/taxonomy asle ter number alada hoy
 * sei jonno sei onusare ID ta ber korar jonno wpml_object_id filter ta use korechi
 *
 * @param Array $terms_data
 * @return Array
 */
function wcmmq_tems_based_wpml( $terms_data ){

    $temp_term = array();
    foreach( $terms_data as $key=>$e_temp ){
        
        foreach( $e_temp as $k=>$val ){
            unset($e_temp[$k]);
            $id = apply_filters( 'wpml_object_id', $k, $key, TRUE);
            $e_temp[$id] =$val;
        }
        $temp_term[$key] = $e_temp;
    }

    return $temp_term;
}

/**
 * Single dimension array of tems 
 * to wpml supported term ids
 * convertion
 * 
 * I have made it for Admin part actually for first time,
 * but it can be use in front-end later, thats why
 * I have make this function to frontEnd functions.php file
 *
 * @param array $term_ids Array of terms ids
 * @param array $taxonomy_name tame of taxonomy key such: product_cat,product_tag etc
 * @return array
 */
function wcmmq_term_ids_wpml( $term_ids, $taxonomy_name ){
    if( ! is_array( $term_ids ) ) return $term_ids;
    $term_temp_ids = array();
    foreach( $term_ids as $k=>$val ){
        
        $id = apply_filters( 'wpml_object_id', $k, $taxonomy_name, TRUE);
        $term_temp_ids[$id] =$val;
    }
    return $term_temp_ids;
}

function wcmmq_get_term_data_wpml(){
    $terms_data = WC_MMQ::getOption( 'terms' );
    $terms_data = is_array( $terms_data ) ? $terms_data : array();
    return wcmmq_tems_based_wpml( $terms_data );
}

function wcmmq_get_message( $keyword, $prefix = WC_MMQ_PREFIX ){
    $f_keyword = $prefix . $keyword; //'msg_min_limit'


    $lang = apply_filters( 'wpml_current_language', NULL );
    $default_lang = apply_filters('wpml_default_language', NULL );

    
    if( $lang !== $default_lang ){
        $f_keyword .= '_' . $lang;
    }
                      
    return WC_MMQ::getOption( $f_keyword );
}

// add_action('woocommerce_single_product_summary','skdkslsl_skslsl');

// function skdkslsl_skslsl(){
//     $aaa = wc_format_localized_price('145.33');
//     // $aaa = wc_get_price_decimal_separator();

//     var_dump(wc_get_price_decimals());
//     // var_dump(wc_format_decimal('124,22'));
// }

/**
* start the customisation
*/
// add_action('woocommerce_before_shop_loop', function() {
//     add_filter('woocommerce_loop_add_to_cart_link', 'wcmmq_add_to_cart', 10, 3);
// });

/**
* customise Add to Cart link/button for product loop
* @param string $button
* @param object $product
* @param array $link
* @return string
*/
function wcmmq_add_to_cart($button, $product, $link) {
    $product_type = $product->get_type();
    var_dump(get_option('woocommerce_enable_ajax_add_to_cart'));
    // return $button;
    // not for variable, grouped or external products
    if (!in_array($product_type, array('variable', 'grouped', 'external'))) {
        // only if can be purchased
        if ($product->is_purchasable()) {
            // show qty +/- with button
            ob_start();
            woocommerce_simple_add_to_cart();
            $button = ob_get_clean();
        }
    }elseif( $product_type == 'variable' ){
        if ($product->is_purchasable()) {
            //woocommerce_template_single_add_to_cart
            //woocommerce_template_loop_add_to_cart
            // show qty +/- with button
            ob_start();
            woocommerce_template_single_add_to_cart();
            $button = ob_get_clean();
        }
    }

    return $button;
}