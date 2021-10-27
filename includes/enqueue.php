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

        /**
         * attrchange js and variation-js file has transferred on pro version.
         * 
         * where it was not working, so it's should not here
         * so we have transferred in pro only.
         */
        
        $product_type = false;
        if( is_product() ){
            $product = wc_get_product( get_the_ID() );
            $product_type = $product->get_type();
        }
        $WCMMQ_DATA = array( 
            'product_type' => $product_type,
            );
        $WCMMQ_DATA = apply_filters( 'wcmmq_localize_data', $WCMMQ_DATA );
        wp_localize_script( 'wcmmq-script', 'WCMMQ_DATA ', $WCMMQ_DATA );
    }
}
add_action( 'wp_enqueue_scripts', 'wcmmq_enqueue', 99 );

