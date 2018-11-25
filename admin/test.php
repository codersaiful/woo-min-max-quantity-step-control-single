<?php
/**
 * <?php do_action( 'woocommerce_product_options_advanced' ); ?>
 * this file only will use for test perpose
 * @since 1.1
 */

add_filter('woocommerce_product_data_tabs','min_max_step_options');
function min_max_step_options( $product_data_tab ){
        $product_data_tab['min-max-step-data'] = array(
            'label' => __('Min Max & Step','wcmmq'),
            'target'   => 'min_max_step',
            'class'     => array('hide_if_grouped'),
            );
        return $product_data_tab;
    
    
  
}
add_filter('woocommerce_product_data_panels','min_max_step_all_options');
function min_max_step_all_options(){
    global $post;
?>
    <div  id="min_max_step" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php
                $args = array(
                    'id'    => '_text_field',
                    'name'  => '_text_field',
                    'label' => 'Minimum Quantity of Product',
                    'wrapper_class' => 'show_if_simple',
                    'type'         => 'text',
                    'desc_tip'  =>true,
                    'descritpion'   => 'Input the minimum quantity of this product.'
                );
                woocommerce_wp_text_input($args);
                
                $args = array(
                    'id'    => '_text_field2',
                    'name'  => '_text_field2',
                    'label' => 'Maximum Quantity of Product',
                    'wrapper_class' => 'show_if_simple',
                    'type'         => 'number',
                    'desc_tip'  =>true,
                    'descritpion'   => 'Input the maximum quantity of this product.'
                );
                woocommerce_wp_text_input($args);
                function wcmmq_save_proddata_custom_fields($post_id) {
                    $text_field = $_POST['_text_field']; 
                    if (!empty($text_field)) { 
                        update_post_meta($post_id, '_text_field', esc_attr($text_field));
                        }
                }
                add_action('woocommerce_process_product_meta','wcmmq_save_proddata_custom_fields');
            
            ?>
        </div>
<?php 
}




//function wcmmq_test_add_in_setting(){
//    $args = array(
//        'id'        =>  '_wcmmq_min_quantityss',
//        'name'        =>  '_wcmmq_min_quantityss',
//        'label'     =>  'Min Quantity',
//        'class'     =>  'wcmmq_input',
//        'type'      =>  'text',
//        'desc_tip'  =>  true,
//        'description'=> 'Somethings Somethings Somethings Somethings Somethings Somethings '
//    );
//    woocommerce_wp_text_input($args);
//}
//add_action('woocommerce_product_options_general_product_data','wcmmq_test_add_in_setting');