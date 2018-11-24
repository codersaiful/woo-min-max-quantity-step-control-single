<?php

/**
 * Adding menu as WooCommerce's menu's Submenu
 * check inside Woocommerce Menu
 * 
 * @since 1.0
 */
function wcmmq_add_menu(){
    add_submenu_page( 'woocommerce', 'WC Min Max Quantity', 'Min Max Quantity', 'manage_options', 'wcmmq_min_max_step', 'wcmmq_faq_page_details' );
}
add_action( 'admin_menu','wcmmq_add_menu' );

/**
 * Faq Page for WC Min Max Quantity
 */
function wcmmq_faq_page_details(){
    echo '<h2>WC Min Max Quantity</h2>'; 
    echo '<p style="color: #d00;">Please see following Screenshot: just for getting help</p>';
    echo '<img style="clear:both;width:100%;height: auto;" src="' . WC_MMQ_BASE_URL .'/images/tips.png">';
/**
    var_dump(WC_MMQ_PLUGIN_BASE_FOLDER);
    var_dump(WC_MMQ_PLUGIN_BASE_FILE);
    var_dump(WC_MMQ_BASE_URL);
$abc = new WC_MMQ();
    
    echo '<h2>WC Min Max Quantity</h2>';
    $args = array(
        'posts_per_page'    =>  3,
        'post_type'         =>  array('product'),
        'post_status'       =>  'publish',  
        'tax_query'         => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => array(17),
                'operator' => 'IN'
            ),
        ),
    );

    $wcmmq_loop = new WP_Query( $args );

    if( $wcmmq_loop->have_posts() ): while( $wcmmq_loop->have_posts() ): $wcmmq_loop->the_post();
            $id = get_the_ID();
            $wcmmq_product = wc_get_product($id);
            //var_dump($wcmmq_product->get_data_keys());
            //var_dump($wcmmq_product->get_data_store());
            var_dump( get_post_meta( $id, '_wcmmq_min_quantity',true ) );
            var_dump( get_post_meta( $id, '_wcmmq_max_quantity',true ) );
            echo '<hr>';

    endwhile;
    wp_reset_query();
    else: 
        echo 'There is no Product';
    endif;

 */
}

