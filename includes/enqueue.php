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

    }
}
add_action( 'wp_enqueue_scripts', 'wcmmq_enqueue', 99 );

