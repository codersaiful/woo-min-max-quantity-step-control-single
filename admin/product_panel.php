<?php

/**
 * To define Tab Menu Under single product edit page
 * We have used a filter: woocommerce_product_data_tabs to To define Tab Menu Under single product edit page
 * 
 * @param Array $product_data_tab
 * @return Array it will return Tabs Array
 */
function wcmmq_s_product_edit_tab( $product_data_tab){
    /*
    $product_data_tab['wcmmq_s_min_max_step'] = array(
            'label' => __('Min Max & Step','wcmmq'),
            'target'   => 'wcmmq_s_min_max_step', //This is targetted div's id
            'class'     => array('show_if_simple'),//array('hide_if_grouped','hide_if_downloadable'),
            );
    return $product_data_tab;
    */

    $my_tab['wcmmq_s_min_max_step'] = array(
        'label' => __('Min Max & Step','wcmmq'),
        'target'   => 'wcmmq_s_min_max_step', //This is targetted div's id
        'class'     => array('hide_if_grouped','hide_if_downloadable'),
        );

    $position = 1; // Change this for desire position 
    $tabs = array_slice( $product_data_tab, 0, $position, true ); // First part of original tabs 
    $tabs = array_merge( $tabs, $my_tab ); // Add new 
    $tabs = array_merge( $tabs, array_slice( $product_data_tab, $position, null, true ) ); // Glue the second part of original 
    return $tabs; //return $product_data_tab;

}
add_filter('woocommerce_product_data_tabs','wcmmq_s_product_edit_tab');

/**
 * For Tab options of Min Max Step
 * We also add a new action to this function name: woocommerce_product_options_wcmmq_s_minmaxstep
 * To add options filed to here
 * 
 * @since 1.0.2
 */
function wcmmq_s_product_tab_options(){
?>
    <div  id="wcmmq_s_min_max_step" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php do_action( 'woocommerce_product_options_wcmmq_s_minmaxstep' ); ?>
        </div>
    </div>
<?php 
}
add_filter('woocommerce_product_data_panels','wcmmq_s_product_tab_options');



/**
 * Displaying Notice to our plugin for rating issue
 * 
 * @return Notice displaying a notice void
 * @since 1.9
 */
function wcmmq_s_important_notice_for_users() {
    if(!class_exists('WC_MMQ')){
        $current_page = get_current_screen();
        if($current_page->base == 'woocommerce_page_wcmmq_s_min_max_step'){
        ob_start();
        ?>
        <div class="notice notice-success is-dismissible">
            <p> <?php echo sprintf( esc_html__('We are working hard to serve BETTER Experience. If you like our %s %s WooCommerce Min Max  %s %s  plugin. Please leave a rating. It will inspire us to develop more features.','wptf_pro'), '<a href="https://wordpress.org/support/plugin/woo-min-max-quantity-step-control-single" target="_blank">','<strong>','</strong>','</a>') ?></p>
            <h3><a href="https://wordpress.org/support/plugin/woo-min-max-quantity-step-control-single/reviews" target="_blank"><?php echo esc_html__('Yes ! You Deserve it.','wptf_pro') ?></a></h3>
            
        </div>
        <?php 
       echo ob_get_clean();
        }
   }
}
add_action('admin_notices','wcmmq_s_important_notice_for_users');