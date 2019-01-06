<?php

/**
 * Adding menu as WooCommerce's menu's Submenu
 * check inside Woocommerce Menu
 * 
 * @since 1.0
 */
function wcmmq_add_menu(){
    add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', 'manage_options', 'wcmmq_min_max_step', 'wcmmq_faq_page_details' );
}
add_action( 'admin_menu','wcmmq_add_menu' );

/**
 * Faq Page for WC Min Max Quantity
 */
function wcmmq_faq_page_details(){
    
    /**********************
    update_option( WC_MMQ::KEY, array(
        '_wcmmq_min_quantity'   => 2,
        '_wcmmq_max_quantity'   =>  22,
        '_wcmmq_product_step'   => 2,
    ));
    //****************************/
    if( isset( $_POST['data'] ) && isset( $_POST['reset_button'] ) ){
        //Reset 
        $data = WC_MMQ::getDefaults();
        //var_dump($value);
        update_option( WC_MMQ::KEY, $data );
        echo '<div class="updated inline"><p>Reset Successfully</p></div>';
    }else if( isset( $_POST['data'] ) && isset( $_POST['configure_submit'] ) ){
        //configure_submit
        $values = ( is_array( $_POST['data'] ) ? $_POST['data'] : false );
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
            $data = WC_MMQ::getDefaults();
        }
        
        if( !$data['_wcmmq_min_quantity'] && $data['_wcmmq_min_quantity'] != 0 &&  $data['_wcmmq_min_quantity'] !=1 && $data['_wcmmq_max_quantity'] <= $data['_wcmmq_min_quantity'] ){
            $data['_wcmmq_max_quantity'] = $data['_wcmmq_min_quantity'] + 5;
            echo '<div class="error notice"><p>Maximum Quantity can not be smaller, So we have added 5</p></div>';
        }
        if( !$data['_wcmmq_product_step'] || $data['_wcmmq_product_step'] == '0' || $data['_wcmmq_product_step'] == 0 ){
           $data['_wcmmq_product_step'] = 1; 
        }
        
        if( !$data['_wcmmq_min_quantity'] || $data['_wcmmq_min_quantity'] == '0' || $data['_wcmmq_min_quantity'] == 0 ){
           $data['_wcmmq_min_quantity'] = 1; 
        }
        
        
        if(is_array( $data ) && count( $data ) > 0 ){
            foreach($data as $key=>$value){
                $val = str_replace('\\', '', $value );
                $final_data[$key] = $val;
            }
        }
        update_option( WC_MMQ::KEY, $final_data);
        echo '<div class="updated inline"><p>Successfully Updated</p></div>';
    }
    
    
    $saved_data = WC_MMQ::getOptions();
?>
<div class="wrap wcmmq_wrap">
    <div class="fieldwrap">
        
        <form action="" method="POST">
            <div class="wcmmq_white_board">
                <span class="configure_section_title">Settings (Universal)</span>
                <table class="wcmmq_config_form">
                    <tr>
                        <th>Minimum Quantity</th>
                        <td>
                            <input name="data[_wcmmq_min_quantity]" value="<?php echo $saved_data['_wcmmq_min_quantity']; ?>"  type="number" step=any>
                        </td>

                    </tr>

                    <tr>
                        <th>Maximum Quantity</th>
                        <td>
                            <input name="data[_wcmmq_max_quantity]" value="<?php echo $saved_data['_wcmmq_max_quantity']; ?>"  type="number" step=any>
                        </td>

                    </tr>

                    <tr>
                        <th>Quantity Step</th>
                        <td>
                            <input name="data[_wcmmq_product_step]" value="<?php echo $saved_data['_wcmmq_product_step']; ?>"  type="number" step=any>
                        </td>

                    </tr>

                </table>
                <span class="configure_section_title">Messages</span>
                <table class="wcmmq_config_form wcmmq_config_form_message">
                    <tr>
                        <th>Minimum Quantity Validation Message</th>
                        <td>
                            <input name="data[_wcmmq_msg_min_limit]" value="<?php echo esc_attr( $saved_data['_wcmmq_msg_min_limit'] ); ?>"  type="text">
                        </td>

                    </tr>
                    <tr>
                        <th>Maximum Quantity Validation Message</th>
                        <td>
                            <input name="data[_wcmmq_msg_max_limit]" value="<?php echo htmlentities( $saved_data['_wcmmq_msg_max_limit'] ); ?>"  type="text">
                        </td>

                    </tr>
                    <tr>
                        <th>Already in cart message</th>
                        <td>
                            <input name="data[_wcmmq_msg_max_limit_with_already]" value="<?php echo esc_attr( $saved_data['_wcmmq_msg_max_limit_with_already'] ); ?>"  type="text">
                        </td>
                    </tr>
                    <tr>
                        <th>Minimum Quantity message for shop page</th>
                        <td>
                            <input name="data[_wcmmq_min_qty_msg_in_loop]" value="<?php echo esc_attr( $saved_data['_wcmmq_min_qty_msg_in_loop'] ); ?>"  type="text">
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <button type="submit" name="configure_submit" class="button-primary primary button btn-info">Submit</button>
            <button type="submit" name="reset_button" class="button">Reset</button>
                    
        </form>
    </div>
</div>  
<style>
span.configure_section_title {
    font-size: 18px;
    width: 102%;
    background: #4CAF50;
    color: #f3f3f3;
    padding: 5px;
    line-height: 18px;
    text-transform: uppercase;
    font-weight: normal;
    padding-right: 0px;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
    display: block !important;
}
.wcmmq_config_form th {
    width: 300px;
    padding: 15px;
    text-align: left;
}
.wcmmq_config_form_message tr td>input{width: 350px;}
.wcmmq_white_board {
    display: block;
    background: #ffffff;
    padding: 0;
    overflow: hidden;
}
</style>
<?php
    //echo '<h2>WC Min Max Quantity</h2>'; 
    //echo '<p style="color: #d00;">Please see following Screenshot: just for getting help</p>';
    //echo '<img style="clear:both;width:100%;height: auto;" src="' . WC_MMQ_BASE_URL .'/images/tips.png">';
/**
    var_dump(WC_MMQ_PLUGIN_BASE_FOLDER);
    var_dump(WC_MMQ_PLUGIN_BASE_FILE);
    var_dump(WC_MMQ_BASE_URL);
$abc = new WC_MMQ();
    
    echo '<h2>WC Min Max Quantity</h2>';
    $args = array(
        'posts_per_page'    =>  3,
        'post_type'         =>  array('product'),
        'post_status'       =>  'publish',  
        'tax_query'         => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => array(17),
                'operator' => 'IN'
            ),
        ),
    );

    $wcmmq_loop = new WP_Query( $args );

    if( $wcmmq_loop->have_posts() ): while( $wcmmq_loop->have_posts() ): $wcmmq_loop->the_post();
            $id = get_the_ID();
            $wcmmq_product = wc_get_product($id);
            //var_dump($wcmmq_product->get_data_keys());
            //var_dump($wcmmq_product->get_data_store());
            var_dump( get_post_meta( $id, '_wcmmq_min_quantity',true ) );
            var_dump( get_post_meta( $id, '_wcmmq_max_quantity',true ) );
            echo '<hr>';

    endwhile;
    wp_reset_query();
    else: 
        echo 'There is no Product';
    endif;

 */
}

