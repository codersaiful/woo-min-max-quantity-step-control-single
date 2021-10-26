<?php
/**
 * Obviously need tr and td here
 */
function wcmmq_before_form_free_content(){

    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;

?>
<div>
    <h2>Free Topbar</h2>
</div>    
<?php    

}
add_action( 'wcmmq_before_form','wcmmq_before_form_free_content' );

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
