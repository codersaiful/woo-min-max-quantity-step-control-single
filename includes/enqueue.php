<?php
/**
 * Enqueue for WCMMQ - WC Min Max Step Control Plugin
 * Mainly Added for Variation Min Max Step feature added, Working on Ajax
 * @since 1.8
 * @date 18.4.2020
 */
function wcmmq_enqueue_fronts(){

    

}
add_action( 'wp_enqueue_scripts', 'wcmmq_enqueue_fronts', 99 );
if( !function_exists( 'wcmmq_enqueue' ) ){
    /**
     * CSS or Style file add for FrontEnd Section. 
     * 
     * @since 1.0.0
     */
    function wcmmq_enqueue(){

        

        wp_register_style( 'wcmmq-front-style', WC_MMQ_BASE_URL . 'assets/wcmmq-front.css', false, '1.0.0' );
        wp_enqueue_style( 'wcmmq-front-style' );

        /**
         * A simple jQuery function that can add listeners on attribute change.
         * http://meetselva.github.io/attrchange/
         * 
         * @since 1.9
         */
        wp_register_script( 'attrchange', WC_MMQ_BASE_URL . 'assets/js/attrchange.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'attrchange' );
        
        
        wp_register_script( 'wcmmq-script', WC_MMQ_BASE_URL . 'assets/variation-wcmmq.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'wcmmq-script' );
        
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

/**
 * Load Script Under Variation form
 * for set QTY based on Variation
 * 
 * @since 1.8
 */
function wcmmq_js_for_variation_product(){
global $product;
$validation = apply_filters( 'wcmmq_js_variation_script', true, $product );

if( !$validation || 'variable' !== $product->get_type() ){
    return;
}

$product_id = $product->get_id();
$product_data['_wcmmq_min_quantity'] = get_post_meta( $product_id, '_wcmmq_min_quantity', true );
$product_data['_wcmmq_default_quantity'] = get_post_meta( $product_id, '_wcmmq_default_quantity', true );
$product_data['_wcmmq_max_quantity'] = get_post_meta( $product_id, '_wcmmq_max_quantity', true );
$product_data['_wcmmq_product_step'] = get_post_meta( $product_id, '_wcmmq_product_step', true );

$product_data = apply_filters( 'wcmmq_product_data_for_json', $product_data, $product );

$product_data = wp_json_encode( $product_data );

$default_data['_wcmmq_min_quantity'] = WC_MMQ::getOption( '_wcmmq_min_quantity' );
$default_data['_wcmmq_default_quantity'] = WC_MMQ::getOption( '_wcmmq_default_quantity' );
$default_data['_wcmmq_max_quantity'] = WC_MMQ::getOption( '_wcmmq_max_quantity' );
$default_data['_wcmmq_product_step'] = WC_MMQ::getOption( '_wcmmq_product_step' );

//For Taxonomy
$terms_data = WC_MMQ::getOption( 'terms' );
$terms_data = is_array( $terms_data ) ? $terms_data : array();
foreach( $terms_data as $term_key => $values ){
    $product_term_list = wp_get_post_terms( $product_id, $term_key, array( 'fields' => 'ids' ));
    foreach ( $product_term_list as $product_term_id ){

        $my_term_value = isset( $values[$product_term_id] ) ? $values[$product_term_id] : false;
        if( is_array( $my_term_value ) ){
            $default_data['_wcmmq_min_quantity'] = !empty( $my_term_value['_min'] ) ? $my_term_value['_min'] : $default_data['_wcmmq_min_quantity'];
            $default_data['_wcmmq_default_quantity'] = !empty( $my_term_value['_default'] ) ? $my_term_value['_default'] : $default_data['_wcmmq_default_quantity'];
            $default_data['_wcmmq_max_quantity'] = !empty( $my_term_value['_max'] )  ? $my_term_value['_max'] : $default_data['_wcmmq_max_quantity'];
            $default_data['_wcmmq_product_step'] = !empty( $my_term_value['_step'] ) ? $my_term_value['_step'] : $default_data['_wcmmq_product_step'];
            break;
        }
    }

}

$default_data = apply_filters( 'wcmmq_default_data_for_json', $default_data, $product );

$default_data = wp_json_encode( $default_data );
$variables = $product->get_children();
//var_dump(count( $variables ) > 0);
$data = array();
if(!is_array($variables) || ( is_array( $variables ) && count( $variables ) < 1 )) return;

foreach( $variables as $variable_id){
    //$min_qty = get_post_meta( $variable_id, '_wcmmq_min_quantity', true );
    $data[$variable_id] = array(
        '_wcmmq_min_quantity' => get_post_meta( $variable_id, '_wcmmq_min_quantity', true ),
        '_wcmmq_default_quantity' => get_post_meta( $variable_id, '_wcmmq_default_quantity', true ),
        '_wcmmq_max_quantity' => get_post_meta( $variable_id, '_wcmmq_max_quantity', true ),
        '_wcmmq_product_step' => get_post_meta( $variable_id, '_wcmmq_product_step', true ),
    );
}
$data = apply_filters( 'wcmmq_variation_data_for_json', $data, $product );
$data = wp_json_encode( $data );//htmlspecialchars( wp_json_encode( $data ) );





//var_dump($product_data,$default_data,$data);
?>
<script  type='text/javascript'>
(function($) {
    'use strict';
    $(document).ready(function($) {
        var product_id = "<?php echo $product->get_id(); ?>";
        var default_data = '<?php echo $default_data; ?>';
        var product_data = '<?php echo $product_data; ?>';
        var variation_data = '<?php echo $data; ?>';
        //var ajax_url = "<?php echo admin_url( 'admin-ajax.php' ); ?>";

        default_data = JSON.parse(default_data);
        product_data = JSON.parse(product_data);
        variation_data = JSON.parse(variation_data);
        var form_selector = 'form.variations_form.cart[data-product_id="' + product_id + '"]';
        
        //console.log(variation_data[55]['_wcmmq_min_quantity']);
        //$(form_selector + ' input.variation_id').css('display','none');
        //console.log(variation_data);

        $('body').on('change',form_selector + ' input.variation_id',function(){
           
            //$( form_selector + ' input.input-text.qty.text' ).triggerHandler( 'binodon');
            var variation_id = $(form_selector + ' input.variation_id').val();
            var qty_box = $(form_selector + ' input.input-text.qty.text');

            if(typeof variation_id !== 'undefined' && variation_id !== ''  && variation_id !== ' '){
                var min,max,step,basic;

                min = variation_data[variation_id]['_wcmmq_min_quantity'];
                if(typeof min === 'undefined'){
                    return false;
                }
                if(min === '' || min === false){
                    min = product_data['_wcmmq_min_quantity'];
                }
                if(min === '' || min === false){
                    min = default_data['_wcmmq_min_quantity'];
                }
                max = variation_data[variation_id]['_wcmmq_max_quantity'];
                if(max === '' || max === false){
                    max = product_data['_wcmmq_max_quantity'];
                }
                if(max === '' || max === false){
                    max = default_data['_wcmmq_max_quantity'];
                }
                
                step = variation_data[variation_id]['_wcmmq_product_step'];
                if(step === '' || step === false){
                    step = product_data['_wcmmq_product_step'];
                }
                if(step === '' || step === false){
                    step = default_data['_wcmmq_product_step'];
                }
                basic = variation_data[variation_id]['_wcmmq_default_quantity'];
                if(basic === '' || basic === false){
                    basic = product_data['_wcmmq_default_quantity'];
                }
                if(basic === '' || basic === false){
                    basic = default_data['_wcmmq_default_quantity'];
                }
                
                if(basic === '' || basic === false){
                    basic = min;
                }
                var lateSome = setInterval(function(){

                    qty_box.attr({
                        min:min,
                        max:max,
                        step:step,
                        value:basic
                    });
                    clearInterval(lateSome);
                },500);

            }
            
            
        });

    });
})(jQuery);
</script>
<?php
}
add_action('woocommerce_single_variation','wcmmq_js_for_variation_product');
add_action('wpt_action_variation','wcmmq_js_for_variation_product');