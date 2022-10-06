<?php 
/**
 * Min or Default Qty fix for Gutenburg Block
 *
 * @since 1.7
 *
 * @param type $content
 * @param type $data
 * @param type $product
 * @return String HTML Full Button
 */
function wcmmq_set_min_qt_in_block_loop ($content, $data, $product){
    $product_id = $product->get_id();

    $min_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'min_quantity', true);
    $default_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'default_quantity', true);

    //If not available in single product, than come from default
    $min_quantity = !empty( $min_quantity ) ? $min_quantity : WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'min_quantity',$product_id );
    $default_quantity = !empty( $default_quantity ) ? $default_quantity : WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'default_quantity',$product_id );
    $default_quantity = !empty( $default_quantity ) ? $default_quantity :$min_quantity;



    //$data->button = return '<div class="wp-block-button wc-block-grid__product-add-to-cart">' . $this->get_add_to_cart( $product ) . '</div>';
    $attributes = array(
        'aria-label'       => $product->add_to_cart_description(),
        'data-quantity'    => $default_quantity,
        'data-product_id'  => $product->get_id(),
        'data-product_sku' => $product->get_sku(),
        'rel'              => 'nofollow',
        'class'            => 'wp-block-button__link add_to_cart_button',
    );

    if ( $product->supports( 'ajax_add_to_cart' ) ) {
        $attributes['class'] .= ' ajax_add_to_cart';
    }

    $saiful_test = sprintf(
        '<a href="%s" %s>%s</a>',
        esc_url( $product->add_to_cart_url() ),
        wc_implode_html_attributes( $attributes ),
        esc_html( $product->add_to_cart_text() )
    );

    $data->button = '<div class="wp-block-button wc-block-grid__product-add-to-cart">' . $saiful_test . '</div>';

    return "<li class=\"wc-block-grid__product\">
                        <a href=\"{$data->permalink}\" class=\"wc-block-grid__product-link\">
                                {$data->image}
                                {$data->title}
                        </a>
                        {$data->badge}
                        {$data->price}
                        {$data->rating}
                        {$data->button}
                </li>";
}
add_filter( 'woocommerce_blocks_product_grid_item_html', 'wcmmq_set_min_qt_in_block_loop', 10, 3 );
