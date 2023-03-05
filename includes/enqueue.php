<?php
/**
 * Enqueue for WCMMQ - WC Min Max Step Control Plugin
 * Mainly Added for Variation Min Max Step feature added, Working on Ajax
 * @since 1.8
 * @date 18.4.2020
 */

if( !function_exists( 'wcmmq_enqueue' ) ){
    /**
     * CSS or Style file add for FrontEnd Section. 
     * 
     * @since 1.0.0
     */
    function wcmmq_enqueue(){

        

        wp_register_style( 'wcmmq-front-style', WC_MMQ_BASE_URL . 'assets/css/wcmmq-front.css', false, '1.0.0' );
        wp_enqueue_style( 'wcmmq-front-style' );

        wp_register_script( 'wcmmq-custom-script', WC_MMQ_BASE_URL . 'assets/js/custom.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'wcmmq-custom-script' );

        /**
         * attrchange js and variation-js file has transferred on pro version.
         * 
         * where it was not working, so it's should not here
         * so we have transferred in pro only.
         */


         /**
          * Localize data added for javascript file
          * Specially need when need decimal style change
          * @author Saiful Islam <codersaiful@gmail.com>
          * @version 3.5.1
          */
         $ajax_url = admin_url( 'admin-ajax.php' );
         $WCMMQ_DATA = array( 
            'ajax_url'       => $ajax_url,
            'site_url'       => site_url(),
            'cart_url'       => wc_get_cart_url(),
            'priceFormat'    => get_woocommerce_price_format(),
            'decimal_separator'=> '.',
            'default_decimal_separator'=> wc_get_price_decimal_separator(),
            'decimal_count'=> wc_get_price_decimals(),
            );

        if(wc_get_price_decimal_separator() == ','){
            $options = WC_MMQ::getOptions();
            $WCMMQ_DATA['decimal_separator'] = ! empty( $options['decimal_separator'] ) ? $options['decimal_separator'] : '.' ;
        }
        $WCMMQ_DATA = apply_filters( 'wcmmq_localize_data', $WCMMQ_DATA );
        wp_localize_script( 'wcmmq-custom-script', 'WCMMQ_DATA', $WCMMQ_DATA );
    }
}
add_action( 'wp_enqueue_scripts', 'wcmmq_enqueue', 99 );

