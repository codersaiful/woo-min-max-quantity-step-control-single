<?php

/**
 * Adding menu as WooCommerce's menu's Submenu
 * check inside Woocommerce Menu
 * 
 * @since 1.0
 */
function wcmmq_s_add_menu(){
    add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', 'manage_options', 'wcmmq_s_min_max_step', 'wcmmq_s_faq_page_details' );
}
add_action( 'admin_menu','wcmmq_s_add_menu' );

/**
 * Faq Page for WC Min Max Quantity
 */
function wcmmq_s_faq_page_details(){

    /**********************
    update_option( WC_MMQ_S::KEY, array(
        '_wcmmq_s_min_quantity'   => 2,
        '_wcmmq_s_max_quantity'   =>  22,
        '_wcmmq_s_product_step'   => 2,
    ));
    //****************************/
    if( isset( $_POST['data'] ) && isset( $_POST['reset_button'] ) ){
        //Reset 
        $data = WC_MMQ_S::getDefaults();
        //var_dump($value);
        update_option( WC_MMQ_S::KEY, $data );
        echo '<div class="updated inline"><p>Reset Successfully</p></div>';
    }else if( isset( $_POST['data'] ) && isset( $_POST['configure_submit'] ) ){
        //Confirm Manage option permission
        if( !current_user_can('manage_options') ){
            return;
        }
        //Nonce verify
        if ( ! isset( $_POST['wcmmq_s_nonce'] ) ) { // Check if our nonce is set.
			return;
	}
        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        if( !wp_verify_nonce( $_POST['wcmmq_s_nonce'], plugin_basename(__FILE__) ) ) {
                return;
        }
        //configure_submit
        $values = ( isset($_POST['data']) && is_array( $_POST['data'] ) ?$_POST['data'] : false );
        $data = $final_data = array();
        if( is_array( $values ) && count( $values ) > 0 ){
            foreach( $values as $key=>$value ){
                if( empty( $value ) ){
                   $data[$key] = false; 
                }else{
                   $data[$key] = $value;  
                }
            }
        }else{
            $data = WC_MMQ_S::getDefaults();
        }
        /*
         * removed for single product min max quanity 
         * 
        if( !$data['_wcmmq_s_min_quantity'] && $data['_wcmmq_s_min_quantity'] != 0 &&  $data['_wcmmq_s_min_quantity'] !=1 && $data['_wcmmq_s_max_quantity'] <= $data['_wcmmq_s_min_quantity'] ){
            $data['_wcmmq_s_max_quantity'] = $data['_wcmmq_s_min_quantity'] + 5;
            echo '<div class="error notice"><p>Maximum Quantity can not be smaller, So we have added 5</p></div>';
        }
        if( !$data['_wcmmq_s_product_step'] || $data['_wcmmq_s_product_step'] == '0' || $data['_wcmmq_s_product_step'] == 0 ){
           $data['_wcmmq_s_product_step'] = 1; 
        }
        
        */
        if( !$data['_wcmmq_s_min_quantity'] || $data['_wcmmq_s_min_quantity'] == '0' || $data['_wcmmq_s_min_quantity'] == 0 ){
           $data['_wcmmq_s_min_quantity'] = 0; 
        }
        
        if(is_array( $data ) && count( $data ) > 0 ){
            foreach($data as $key=>$value){
                $val = str_replace('\\', '', $value );
                $final_data[$key] = $val;
            }
        }
        update_option( WC_MMQ_S::KEY, $final_data);
        echo '<div class="updated inline"><p>Successfully Updated</p></div>';
    }
    
    
    $saved_data = WC_MMQ_S::getOptions();
?>
<div class="wrap wcmmq_s_wrap">
    <h2>Form</h2>
    <div class="wcmmq_fieldwrap">
        <form action="" method="POST">
             <input type="hidden" name="wcmmq_s_nonce" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ) ?>" />
            <div class="wcmmq_s_white_board">
                <span class="configure_section_title">Messages</span>
                <table class="wcmmq_s_config_form wcmmq_s_config_form_message">
                    <tr>
                        <th>Minimum Quantity Validation Message</th>
                        <td>
                            <input name="data[_wcmmq_s_msg_min_limit]" value="<?php echo esc_attr( $saved_data['_wcmmq_s_msg_min_limit'] ); ?>"  type="text">
                        </td>

                    </tr>
                    <tr>
                        <th>Maximum Quantity Validation Message</th>
                        <td>
                            <input name="data[_wcmmq_s_msg_max_limit]" value="<?php echo esc_attr( $saved_data['_wcmmq_s_msg_max_limit'] ); ?>"  type="text">
                        </td>

                    </tr>
                    <tr>
                        <th>Already in cart message</th>
                        <td>
                            <input name="data[_wcmmq_s_msg_max_limit_with_already]" value="<?php echo esc_attr( $saved_data['_wcmmq_s_msg_max_limit_with_already'] ); ?>"  type="text">
                        </td>
                    </tr>
                    <tr>
                        <th>Minimum Quantity message for shop page</th>
                        <td>
                            <input name="data[_wcmmq_s_min_qty_msg_in_loop]" value="<?php echo esc_attr( $saved_data['_wcmmq_s_min_qty_msg_in_loop'] ); ?>"  type="text">
                        </td>
                    </tr>
                </table>
                <div class="wcmmq_s_waring_msg"><i>Important Note</i>: Don't change [<b>%s</b>], because it will work as like  variable. Here 1st [<b>%s</b>] will return Quantity(min/max) and second [<b>%s</b>] will return product's name.</div>
                <br>
            <button type="submit" name="configure_submit" class="button-primary primary button btn-info">Submit</button>
            <button type="submit" name="reset_button" class="button">Reset</button>
                
            </div>
             <div class="wcmmq_s_white_board">
                 <span class="configure_instruction">You will get the option to set Min Max Quantity of a proudct in the product data panel. Just Like This Screenshot.</span>
                 <img class="config_instruction_img" src="<?php echo WC_MMQ_S_BASE_URL; ?>admin/wcmmq-single-product-quantity.png" >
                 <br>
                 <hr>
                 <br>
                 <h1>Pro Features - At a Glance | <a href="https://codecanyon.net/item/woocommerce-min-max-quantity-step-control/22962198" target="_blank">Get Pro</a></h1>
                 
                 <ul class="wcmmq_s_pro_features_list">
                     <li>Decimal Min, Decimal Max, Decimal Step supported</li>
                     <li>Support Universal Min Max Step - where user will able to set min max step for One place</li>
                     <li>And So on...</li>
                 </ul>
                 <img style="max-width: 100%;"src="<?php echo WC_MMQ_S_BASE_URL; ?>images/pro_features.png">
             </div>
            
        </form>
    </div>
    <?php include_once 'includes/right_side.php'; ?>
</div>  

<?php
}

function wcmmq_s_load_custom_wp_admin_style() {
        wp_register_style( 'wcmmq_s_css', WC_MMQ_S_BASE_URL . 'admin/wcmmq_s_style.css', false, WC_MMQ_S::getVersion() );
        wp_enqueue_style( 'wcmmq_s_css' );
}
add_action( 'admin_enqueue_scripts', 'wcmmq_s_load_custom_wp_admin_style' );
