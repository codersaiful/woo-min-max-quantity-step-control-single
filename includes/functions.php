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
 * ei part tuku ami korechilam,
 * woocommerce er setting page a new tab add korar option khujar jonno.
 * 
 * ekhono paini.
 */
// add_filter('woocommerce_get_settings_pages','wcmmq_woocommerce_settings_tabs_array');
// function wcmmq_woocommerce_settings_tabs_array($arr)
// {
//     var_dump($arr);
//     return $arr;
// }

// add_filter('woocommerce_product_tabs','wcmmq_woocommerce_settings_tabs_array');
// add_filter('woocommerce_settings_tabs_array','wcmmq_woocommerce_settings_tabs_array');
// function wcmmq_woocommerce_settings_tabs_array($arr)
// {
//     var_dump($arr);
//     return $arr;
// }


add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
function woocommerce_ajax_add_to_cart()
{
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        WC_AJAX::get_refreshed_fragments();
    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        );

        echo wp_send_json($data);
    }

    wp_die();
}
