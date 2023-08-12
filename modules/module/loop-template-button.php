<?php
// namespace WC_MMQ\Modules\Module;

// use WC_MMQ\Includes\Min_Max_Controller;
// class Loop_Template_Button extends Min_Max_Controller{
//     public function __construct()
//     {
//         parent::__construct();

//         // add_action('woocommerce_loop_add_to_cart_args',[$this, 'woocommerce_loop_add_to_cart_args'] );
//         // add_action('woocommerce_before_shop_loop',[$this, 'acttt'] );
//         // add_action('woocommerce_after_single_product_summary',[$this, 'acttt'] );
//         // var_dump($this->input_args);
//     }
//     public function acttt()
//     {
//         add_filter('woocommerce_loop_add_to_cart_link',[$this, 'shop_loop'],10,3);
//     }
//     public function shop_loop($button, $product, $args)
//     {
//         var_dump($args);
//         if(!$product) return $button;
//         if( $product->is_sold_individually() ) return $button;
//         $this->product = $product;
//         $this->product_id = $this->product->get_id();
//         $this->get_product_type = $this->product->get_type();

//         if( is_single() && 'variable' === $this->product->get_type() ){
//             $this->variation_id = $args['variation_id'] ?? 0;
//             $this->variation_product = wc_get_product( $this->variation_id );
//         }elseif('variation' === $this->product->get_type() ){
//             //As it's variation product, So I have to assign variation id and product at the begining this statement
//             $this->variation_id = $this->product->get_id();
//             $this->variation_product = wc_get_product( $this->variation_id );
//             $this->get_variation_type = $this->variation_product->get_type();

//             $this->product_id = $this->product->get_parent_id();
//             $this->product = wc_get_product( $this->product_id );
//         }else{
//             $this->variation_id = null;
//             $this->variation_product = null;
//         }

//         //Need to set organize args and need to finalize
//         $this->organizeAndFinalizeArgs();
//         var_dump($this->input_args);
//         return $button;
//     }
// }
// new Loop_Template_Button();
// var_dump( 444444 );
/**
 * Setting quantity in Loop of Shop Page
 * Related page and Category And tag Loop Page
 *
 * @param string $button
 * @param object $product
 * @param array $args
 * @return string
 */
function wcmmq_set_min_qt_in_shop_loop($button = false,$product = false,$args = false){
    if( $button && $product ):
        $product_type = $product->get_type();
        $additional_class = $product_type !== 'variable' && $product_type !== 'grouped' && $product_type !== 'external' ? 'add_to_cart_button ajax_add_to_cart' : '';
        $class = 'button product_type_' . $product_type . ' ' . $additional_class;
        $product_id = $product->get_id();
        $product_name = $product->get_title();
        $min_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'min_quantity', true);
        $default_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'default_quantity', true);
        $max_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'max_quantity', true);
        $step_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'product_step', true);

        // Checking terms here
        $terms_data = wcmmq_get_term_data_wpml();

        if(is_array($terms_data) ){
            foreach( $terms_data as $term_key => $values ){
                $product_term_list = wp_get_post_terms( $product_id, $term_key, array( 'fields' => 'ids' ));
                foreach ( $product_term_list as $product_term_id ){

                    $my_term_value = isset( $values[$product_term_id] ) ? $values[$product_term_id] : false;
                    if( is_array( $my_term_value ) ){
                        $min_quantity = !empty( $min_quantity ) ? $min_quantity : $my_term_value['_min'];
                        $default_quantity = !empty( $default_quantity ) ? $default_quantity : $my_term_value['_default'] ?? '';
                        $max_quantity = !empty( $max_quantity )  ? $max_quantity : $my_term_value['_max'];
                        $step_quantity = !empty( $step_quantity ) ? $step_quantity : $my_term_value['_step'];
                        break;
                    }
                }

            }
        }
        //If not available in single product, than come from default
        $min_quantity = !empty( $min_quantity ) ? $min_quantity : \WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'min_quantity',$product_id );
        $default_quantity = !empty( $default_quantity ) ? $default_quantity : \WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'default_quantity',$product_id );
        $default_quantity = !empty( $default_quantity ) ? $default_quantity :$min_quantity;
        $max_quantity = !empty( $max_quantity ) ? $max_quantity : \WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'max_quantity',$product_id );
        $step_quantity = !empty( $step_quantity ) ? $step_quantity : \WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'product_step',$product_id );


        if( ( !empty( $min_quantity ) || !$min_quantity ) && is_numeric($min_quantity) ){
            $args['quantity'] = $default_quantity; //Default Quantity
            $args['max_value'] = $max_quantity;
            $args['min_value'] = $min_quantity;
            $args['step'] = $step_quantity;
        }

        $cart_btn_attr = array(
            'href' => esc_url( $product->add_to_cart_url() ),
            'title' => esc_attr( \WC_MMQ::getOption( WC_MMQ_PREFIX . 'min_qty_msg_in_loop' ) . " " .$args['quantity'] ),
            'quantity' => esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
            'class' => esc_attr( isset( $args['class'] ) ? $args['class'] : $class ),
            'product_id' => $product_id,
            'rel' => 'nofollow',
            'attributes' => isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
            'text' => esc_html( $product->add_to_cart_text() ),
        );

        $cart_btn_attr = apply_filters( 'wcmmq_cart_button_attr_in_loop', $cart_btn_attr );

        $add_to_cart_btn = "<a ";
        if( isset( $cart_btn_attr['href'] ) && ! empty( $cart_btn_attr['href'] ) ){
            $add_to_cart_btn .= "href='{$cart_btn_attr['href']}' ";
        }if( isset( $cart_btn_attr['title'] ) && ! empty( $cart_btn_attr['title'] ) ){
        $add_to_cart_btn .= "title='{$cart_btn_attr['title']}' ";
    }if( isset( $cart_btn_attr['quantity'] ) && ! empty( $cart_btn_attr['quantity'] ) ){
        $add_to_cart_btn .= "data-quantity='{$cart_btn_attr['quantity']}' ";
    }if( isset( $cart_btn_attr['class'] ) && ! empty( $cart_btn_attr['class'] ) ){
        $add_to_cart_btn .= "class='{$cart_btn_attr['class']}' ";
    }if( isset( $cart_btn_attr['rel'] ) && ! empty( $cart_btn_attr['rel'] ) ){
        $add_to_cart_btn .= "rel='{$cart_btn_attr['rel']}' ";
    }if( isset( $cart_btn_attr['attributes'] ) && ! empty( $cart_btn_attr['attributes'] ) ){
        $add_to_cart_btn .= "{$cart_btn_attr['attributes']} ";
    }
        $add_to_cart_btn .= ">{$cart_btn_attr['text']}</a>";

        return $add_to_cart_btn;

    endif;

    return null;
}

/**
 * Adding filter for Shop Page as well as Related product, which normally show in Single product page at bottom section
 *
 * @link https://docs.woocommerce.com/document/override-loop-template-and-show-quantities-next-to-add-to-cart-buttons/ Details about: Override loop template and show quantities next to add to cart buttons.
 * @since 1.0.14
 */
function wcmmq_add_filter_for_shop_n_related_loop(){
    // add_filter('woocommerce_loop_add_to_cart_link','wcmmq_set_min_qt_in_shop_loop',10,3);
}
// add_action('woocommerce_before_shop_loop','wcmmq_add_filter_for_shop_n_related_loop' );
// add_action('woocommerce_after_single_product_summary','wcmmq_add_filter_for_shop_n_related_loop' );
