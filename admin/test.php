<?php
/**
 * this file only will use for test perpose
 * @since 1.1
 */

function wcmmq_test_add_in_setting(){
    $args = array(
        'id'        =>  '_wcmmq_min_quantityss',
        'name'        =>  '_wcmmq_min_quantityss',
        'label'     =>  'Min Quantity',
        'class'     =>  'wcmmq_input',
        'type'      =>  'text',
        'desc_tip'  =>  true,
        'description'=> 'Somethings Somethings Somethings Somethings Somethings Somethings '
    );
    woocommerce_wp_text_input($args);
}
add_action('woocommerce_product_options_general_product_data','wcmmq_test_add_in_setting');