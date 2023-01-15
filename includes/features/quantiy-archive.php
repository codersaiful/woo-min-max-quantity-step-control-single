<?php
namespace WC_MMQ\Includes\Features;

/**
 * Displaying Quantity Box inside Any type Archive
 * it can shop page, category page, Any taxonomy page 
 * 
 * Or even can be relate product area
 * 
 * @since 3.6.0
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Quantiy_Archive
{
    public $dissupport_arr = [];
    /**
     * Enable Quantiy_Archive or not
     *
     * @return void
     */
    public function run(){
        $this->dissupport_arr = apply_filters( 'wcmmq_archive_qty_dissupport_arr', ['variable','grouped', 'external'] );
        add_action( 'woocommerce_before_shop_loop', [$this, 'customize_shop_loop'] );

        add_action( 'wp_enqueue_scripts', [$this, 'wp_enqueue_scripts'], 99 );
    }

    /**
     * First fucomozed for Shop Loop
     *
     * @return void
     */
    public function customize_shop_loop()
    {
        $link_priority = apply_filters( 'wcmmq_archive_qty_priority', 11 );
        add_filter( 'woocommerce_loop_add_to_cart_link', [$this, 'custom_add_to_cart'], 11, 3 );
    }

    public function custom_add_to_cart( $button, $product, $link )
    {
        $product_type = $product->get_type();

        // not for variable, grouped or external products
        if (! in_array($product_type, $this->dissupport_arr)) {
            // only if can be purchased
            if ($product->is_purchasable()) {
                // show qty +/- with button
                ob_start();
                woocommerce_template_single_add_to_cart();
                $button = ob_get_clean();
            }
        }
        

        /**
         * 
         * 
         * 
         //Previous code
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
         * 
         * 
         */

        return $button;
    }

    public function wp_enqueue_scripts()
    {
        wp_register_script( 'wcmmq-ajax-add-to-cart', WC_MMQ_BASE_URL . 'assets/js/ajax-add-to-cart.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'wcmmq-ajax-add-to-cart' );
    }
}