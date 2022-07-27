<?php

/**
 * To define Tab Menu Under single product edit page
 * We have used a filter: woocommerce_product_data_tabs to To define Tab Menu Under single product edit page
 * 
 * @param Array $product_data_tab
 * @return Array it will return Tabs Array
 */
function wcmmq_product_edit_tab( $product_data_tab){
    /*
    $product_data_tab['wcmmq_min_max_step'] = array(
            'label' => __('Min Max & Step','wcmmq'),
            'target'   => 'wcmmq_min_max_step', //This is targetted div's id
            'class'     => array('show_if_simple'),//array('hide_if_grouped','hide_if_downloadable'),
            );
    return $product_data_tab;
    */

    $my_tab['wcmmq_min_max_step'] = array(
        'label' => __('Min Max & Step','wcmmq'),
        'target'   => 'wcmmq_min_max_step', //This is targetted div's id
        'class'     => array( 'hide_if_downloadable','hide_if_grouped' ), //'hide_if_grouped',
        );

    $position = 1; // Change this for desire position 
    $tabs = array_slice( $product_data_tab, 0, $position, true ); // First part of original tabs 
    $tabs = array_merge( $tabs, $my_tab ); // Add new 
    $tabs = array_merge( $tabs, array_slice( $product_data_tab, $position, null, true ) ); // Glue the second part of original 
    return $tabs; //return $product_data_tab;

}
add_filter('woocommerce_product_data_tabs','wcmmq_product_edit_tab');

/**
 * For Tab options of Min Max Step
 * We also add a new action to this function name: woocommerce_product_options_wcmmq_minmaxstep
 * To add options filed to here
 * 
 * @since 1.0.2
 */
function wcmmq_product_tab_options(){
?>
    <div  id="wcmmq_min_max_step" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php do_action( 'woocommerce_product_options_wcmmq_minmaxstep' ); ?>
            <?php do_action( 'wpt_offer_here' );  ?>
        </div>
    </div>
<?php 
}
add_filter('woocommerce_product_data_panels','wcmmq_product_tab_options');