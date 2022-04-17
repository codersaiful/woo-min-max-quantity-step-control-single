<?php
/**
 * Obviously need tr and td here
 */
function wcmmq_before_form_free_content(){

    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;

?>
<div class="wcmmq-nav">
    <ul>
        <li><a href="https://codeastrology.com/min-max-quantity/documentation/" target="_blank">Documentation</a></li>
        <li><a href="https://wordpress.org/support/plugin/woo-min-max-quantity-step-control-single/reviews/#new-post" target="_blank">Rate on wordpress.org</a></li>
        <li><a href="https://wordpress.org/support/plugin/woo-min-max-quantity-step-control-single/" target="_blank">WordPress Forum</a></li>
        <li><a href="https://codeastrology.com/support/" target="_blank">Need Help?</a></li>
        <li><a href="https://github.com/codersaiful/woo-min-max-quantity-step-control/issues/new" target="_blank">Request Features</a></li>
        <li class="wcmmq-checkout-pro-features"><a href="https://codeastrology.com/min-max-quantity/" target="_blank">Pro Features</a></li>
        <li class="wcmmq-pro-buy-now wcmmq-get-pro-now"><a href="https://codeastrology.com/min-max-quantity/pricing/" target="_blank">Get Pro</a></li>
    </ul>
</div>    
<?php    

}
add_action( 'wcmmq_before_form','wcmmq_before_form_free_content' );


function wcmmq_get_pro_discount_message(){
    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
    $img = WC_MMQ_BASE_URL . 'assets/images/60percent.jpg';
        
    ?>
    <a title="Special Discount for Limited Time." class="special_60_offer" href="https://codeastrology.com/min-max-quantity/pricing/" target="_blank">
        <img style="border-radius: 0;width: 800px;max-width: 100%;" src="<?php echo esc_attr( $img ); ?>">
    </a>    
    <?php 
}

/**
 * Obviously need tr and td here
 */
function wcmmq_category_choose_image($saved_data){
    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
$image_link = WC_MMQ_BASE_URL . 'assets/images/features/setting-bottom.jpg'
?>
<tr class="wcmmq-premium">
    <td colspan="2">
        <img src="<?php echo esc_url($image_link); ?>">
    </td>
</tr>
<?php    

}
add_action( 'wcmmq_setting_bottom_row','wcmmq_category_choose_image' );

function wcmmq_cart_page_condition_feautre($saved_data){
    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
$image_link = WC_MMQ_BASE_URL . 'assets/images/features/min-max-on-cart-page.png'
?>
<div class="wcmmq-premium">
    <div>
        <img src="<?php echo esc_url($image_link); ?>">
    </div>
</div>

<?php    

}
add_action( 'wcmmq_form_panel_before_message','wcmmq_cart_page_condition_feautre' );

function wcmmq_quantity_prefix_sufix_feautre($saved_data){
    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
$image_link = WC_MMQ_BASE_URL . 'assets/images/features/quantity-prefix-sufix.jpg'
?>
<div class="wcmmq-premium">
    <div>
        <img src="<?php echo esc_url($image_link); ?>">
    </div>
</div>

<?php    

}
add_action( 'wcmmq_form_panel_before_message','wcmmq_quantity_prefix_sufix_feautre' );

function wcmmq_cart_page_notices($saved_data){
    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
$image_link = WC_MMQ_BASE_URL . 'assets/images/features/cart-page-notices-settings.png'
?>
<div class="wcmmq-premium">
    <div>
        <img src="<?php echo esc_url($image_link); ?>">
    </div>
</div>

<?php    

}
add_action( 'wcmmq_form_panel_bottom','wcmmq_cart_page_notices' );




