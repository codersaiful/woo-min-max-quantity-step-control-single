<?php
/**
 * Obviously need tr and td here
 */
function wcmmq_before_form_free_content(){

    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
    // wcmmq_donate_button();
?>
<div class="wcmmq-nav">
    <ul>
        <li><a href="https://codeastrology.com/min-max-quantity/documentation/" target="_blank">Documentation</a></li>
        <li><a href="https://wordpress.org/support/plugin/woo-min-max-quantity-step-control-single/reviews/#new-post" target="_blank">Rate on wordpress.org</a></li>
        <li><a href="https://wordpress.org/support/plugin/woo-min-max-quantity-step-control-single/" target="_blank">WordPress Forum</a></li>
        <li><a href="https://codeastrology.com/my-support/" target="_blank">Need Help?</a></li>
        <li><a href="https://github.com/codersaiful/woo-min-max-quantity-step-control/issues/new" target="_blank">Request Features</a></li>
        <li class="wcmmq-checkout-pro-features"><a href="https://codeastrology.com/min-max-quantity/" target="_blank">Pro Features</a></li>
        <li class="wcmmq-pro-buy-now wcmmq-get-pro-now"><a href="https://codeastrology.com/min-max-quantity/pricing/" target="_blank">Get Pro</a></li>
    </ul>
</div>    
<?php    

}
add_action( 'wcmmq_before_form','wcmmq_before_form_free_content' );


function wcmmq_get_pro_discount_message(){
    return;

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
$image_link = WC_MMQ_BASE_URL . 'assets/images/features/setting-bottom.png'
?>
<tr class="wcmmq-premium">
    <td colspan="2">
        <img src="<?php echo esc_url($image_link); ?>">
    </td>
</tr>
<?php    

}
add_action( 'wcmmq_setting_bottom_row','wcmmq_category_choose_image' );

function wcmmq_quantity_prefix_supported_terms($saved_data){
    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
$image_link = WC_MMQ_BASE_URL . 'assets/images/features/supported terms.png'
?>
<div class="wcmmq-premium">
    <div>
        <img src="<?php echo esc_url($image_link); ?>">
    </div>
</div>

<?php    

}
add_action( 'wcmmq_form_panel_before_message','wcmmq_quantity_prefix_supported_terms' );

function wcmmq_quantity_prefix_sufix_feautre($saved_data){
    if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
$image_link = WC_MMQ_BASE_URL . 'assets/images/features/quantity-prefix-sufix.png'
?>
<div class="wcmmq-premium">
    <div>
        <img src="<?php echo esc_url($image_link); ?>">
    </div>
</div>

<?php    

}
add_action( 'wcmmq_form_panel_before_message','wcmmq_quantity_prefix_sufix_feautre' );

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

/**
 * Message Field of Form Genrator
 * In this part
 * I added also WPML field added
 * Example of Field Array:
                $fields_arr = [
                    'msg_min_limit' => [
                        'title' => 'Minimum Quantity Validation Message',
                        'desc'  => 'Available shortcode [min_quantity],[max_quantity],[product_name]',
                    ],
                    
                    'msg_max_limit' => [
                        'title' => 'Maximum Quantity Validation Message',
                        'desc'  => 'Available shortcode [current_quantity][min_quantity],[max_quantity],[product_name]',
                    ],
                ];
 * 
 *
 * @param Array $fields_arr Should be an Array
 * @param Array $saved_data It's a saved data as well as it will come from defaults value
 * @return void
 */
function wcmmq_message_field_generator( $fields_arr, $saved_data, $section_title = 'Message', $prefix = WC_MMQ_PREFIX ){
     $msg_for_langs = __( 'Message', 'wcmmq' );
    
    ?>
<div class="ultraaddons-panel">
    <h2 class="with-background"><?php echo esc_html__( $section_title, 'wcmmq' ); ?></h2>
    <table class="wcmmq_config_form wcmmq_config_form_message">
        <?php
        
        foreach( $fields_arr as $key_name => $messages ){
            
            extract($messages);
            $f_key_name = $prefix . $key_name;
            $value = $saved_data[$f_key_name] ?? '';
            $default_value = WC_MMQ::$default_values[$f_key_name] ?? '';
            $value = ! empty( $value ) ? $value : $default_value;
        ?>
        <tr>
            <th><?php echo esc_html( $title ); ?></th>
            <td>
                
                <?php 

                $settings = array(
                    'textarea_name'     =>'data['. $f_key_name . ']',
                    'textarea_rows'     => 3,
                    'teeny'             => true,
                    );
                wp_editor( esc_attr( $value ), $f_key_name, $settings ); ?>
                <p><?php echo esc_html( $desc ); ?></p>

                <?php
                $lang = apply_filters('wpml_default_language', NULL );
                $active_langs = apply_filters( 'wpml_active_languages', array(), 'orderby=id&order=desc' );
                if( isset( $active_langs[$lang] )){
                    unset($active_langs[$lang]);
                }
                if( empty( $active_langs ) || ! is_array( $active_langs ) ) continue;
                
                ?>

                <div class="language-area" style="border-bottom: 4px solid black;">
                <p class="lang-area-title"><?php echo esc_html__( 'WPML Translate Area', 'wcmmq_pro' ); ?></p>
                <?php
                foreach( $active_langs as $active_lang ){
        
                    $code = $active_lang['code'];
                    $english_name = $active_lang['translated_name'];
                    $native_name = $active_lang['native_name'];
                    $lang_name = $english_name . "({$native_name})";
                    
                    $flag = $active_lang['country_flag_url'];
                ?>
                <p class="wpt-each-input">
                    <lable><img src="<?php echo esc_url( $flag ); ?>" class="wpt-wpml-admin-flag"> <?php echo esc_html( $lang_name ); ?></lable>
                <?php
                $wpml_key_name = $f_key_name . '_' . $code;
                $value = $saved_data[$wpml_key_name] ?? $value;
                $settings = array(
                    'textarea_name'     =>'data['. $wpml_key_name . ']',
                    'textarea_rows'     => 3,
                    'teeny'             => true,
                    );
                wp_editor( esc_attr( $value ), $wpml_key_name, $settings ); 
                ?>
                    
                </p>
                <?php }
                ?>
                </div>
            </td>

        </tr>
        <?php 
        }
        ?>
        
    </table>

</div>
    <?php 
}

if( !function_exists( 'wcmmq_tawkto_code_header' ) ){
    /**
     * set class for Admin Body tag
     * 
     * @param type $classes
     * @return String
     */
    function wcmmq_tawkto_code_header( $class_string ){
        global $current_screen;
        $s_id = isset( $current_screen->id ) ? $current_screen->id : '';
        if( strpos( $s_id, 'wcmmq') !== false ){
        ?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/628f5d4f7b967b1179915ad7/1g4009033';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->      
        <?php
        }
        
    }
}
add_filter( 'admin_head', 'wcmmq_tawkto_code_header', 999 );

function wcmmq_social_links(){
    ?>
    <div class="codeastrogy-social-area-wrapper">
        <?php
        
        $img_folder = WC_MMQ_BASE_URL . 'assets/images/brand/social/';
        $codeastrology = [
            'ticket'   => ['url' => 'https://codeastrology.com/my-support/?utm=Plugin_Social', 'title' => 'Create Ticket'],
            'web'   => ['url' => 'https://codeastrology.com/?utm=Plugin_Social', 'title' => 'CodeAstrology'],
            'wpt'   => ['url' => 'https://wooproducttable.com/?utm=Plugin_Social', 'title' => 'Woo Product Table'],
            'min-max'   => ['url' => 'https://codeastrology.com/min-max-quantity/?utm=Plugin_Social', 'title' => 'CodeAstrology Min Max Step'],
            'linkedin'   => ['url' => 'https://www.linkedin.com/company/codeastrology'],
            'youtube'   => ['url' => 'https://www.youtube.com/c/codeastrology'],
            'facebook'   => ['url' => 'https://www.facebook.com/codeAstrology'],
            'twitter'   => ['url' => 'https://www.twitter.com/codeAstrology'],
            'skype'   => ['url' => '#codersaiful', 'title' => 'codersaiful'],
        ];
        foreach($codeastrology as $key=>$cLogy){
            $image_name = $key . '.png';
            $image_file = $img_folder . $image_name;
            $url = $cLogy['url'] ?? '#';
            $title = $cLogy['title'] ?? false;
            $alt = ! empty( $title ) ? $title : $key;
            $title_available = ! empty( $title ) ? 'title-available' : '';
            
        ?>
        <a class="ca-social-link ca-social-<?php echo esc_attr( $key ); ?> ca-<?php echo esc_attr( $title_available ); ?>" href="<?php echo esc_url( $url ); ?>" target="_blank">
            <img src="<?php echo esc_url( $image_file ); ?>" alt="<?php echo esc_attr( $alt ); ?>"> 
            <span><?php echo esc_html( $title ); ?></span>
        </a>
        <?php 
            
    
        }
        ?>

    </div>
    
    <?php
}

/**
 * This function will display submiting issue section
 * @author Fazle Bari [ fazlebarisn@gmail.com ]
 */
function wcmmq_submit_issue_link(){
    wcmmq_donate_button();
    ?>
    <p class="wpt-issue-submit">
<?php
$content_of_mail = __( 'I have found an issue with your Min Max and Step Control plugin. I will explain here with screenshot.Issues And Screenshots:', 'wcmmq' );
?>
        <b>ISSUE SUBMIT:</b> If you founded any issue, Please inform us. That will be very helpful for us to Fix.
        <a href="https://github.com/codersaiful/woo-min-max-quantity-step-control-single/issues/new" target="_blank">SUBMIT ISSUE</a> or 
        <a href="mailto:contact@codeastrology.com">contact@codeastrology.com</a> or 
        <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&su=<?php echo urlencode("Found issue on your Min Max and Step Control Plugin, see screenshot of issue"); ?>&body=<?php echo esc_attr( $content_of_mail ); ?>&ui=2&tf=1&to=codersaiful@gmail.com,contact@codeastrology.com" target="_blank">Gmail Me</a> or
        <a href="https://www.facebook.com/groups/wphelps" target="_blank">Facebook Group</a>
        <a href="https://codeastrology.com/my-support/?utm_source=plugin-backend&&utm_medium=Free+Version" target="_blank" class="wpt-create-ticket">Create Ticket</a>
    </p>
    <?php
    // wcmmq_donate_button();
}

if( ! function_exists('wcmmq_doc_link') ){
    /**
     * This function will add helper doc
     * @since 3.3.6.1
     * @author Fazle Bari <fazlebarisn@gmail.com>
     */
    function wcmmq_doc_link( $url, $title='Helper doc' ){
        ?>
            <a href="<?php echo esc_url($url)?>" target="_blank" class="wpt-doc-lick"><?php esc_html_e( $title ); ?></a>
        <?php
    }
}

/**
 * Display Donate button for min max plugin.
 *
 * @param boolean $only_free
 * @return void
 */
function wcmmq_donate_button($only_free = false){
    // if($only_free && defined('WPT_PRO_DEV_VERSION')) return;
    ?>
<script async
  src="https://js.stripe.com/v3/buy-button.js">
</script>

<stripe-buy-button
  buy-button-id="buy_btn_1Mh4dDD2lfqrjhAGQMmUm1PX"
  publishable-key="pk_live_51Mg2ndD2lfqrjhAG866UldpkG61JxUK5boTxSFo5hahsnMqyWhAOrqNpCOuj67AaalPgamISySLbl4s4BCDWo7mH00vrDu4ba6"
>
</stripe-buy-button>
    <?php
}
