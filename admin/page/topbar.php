<?php
$min_max_img = WC_MMQ_BASE_URL . 'assets/images/brand/social/min-max.png';

/**
 * This following part actually
 * for our both version
 * 
 * ekta en vato nd arekta amader mull site laisens er jonno.
 * code vato hole to WC_MMQ_PRO::$direct er value null thakbe
 * tokhon amra License menu ta ekhane dekhabo na.
 * 
 * ********************
 * arekta prosno jagte pare.
 * ei lai sense er jonno to ekta property achei amade Page_Loader class e
 * tarpor o keno amra ekhane notun kore check korchi.
 * 
 * asoel ei tobbar.php file ta onno class diye o , jemon \WC_MMQ_PRO\Admin\License\Init class eo 
 * load kora hoyeche. tokhon to $this->license pabe na.
 * tai notun kore check korechi.
 */
$license_direct = true;
if($this->is_pro && class_exists('\WC_MMQ_PRO')){
    $license_direct = \WC_MMQ_PRO::$direct;
}

$topbar_sub_title = __( 'Manage and Settings', 'wcmmq' );
if( isset( $this->topbar_sub_title ) && ! empty( $this->topbar_sub_title ) ){
    $topbar_sub_title = $this->topbar_sub_title;
}
?>
<div class="wcmmq-header wcmmq-clearfix">
    <div class="container-flued">
        <div class="col-lg-7">
            <div class="wcmmq-logo-wrapper-area">
                <div class="wcmmq-logo-area">
                    <img src="<?php echo esc_url( $min_max_img ); ?>" class="wcmmq-brand-logo">
                </div>
                <div class="wcmmq-main-title">
                    <h2 class="wcmmq-ntitle"><?php _e("Min Max Control", "wcmmq");?></h2>
                </div>
                
                <div class="wcmmq-main-title wcmmq-main-title-secondary">
                    <h2 class="wcmmq-ntitle"><?php echo esc_html( $topbar_sub_title );?></h2>
                </div>

            </div>
        </div>
        <div class="col-lg-5">
            <div class="header-button-wrapper">
                <?php if( ! $this->is_pro){ ?>
                    <a class="wcmmq-button reverse" 
                        href="https://codeastrology.com/min-max-quantity/pricing/" 
                        target="_blank">
                        <i class="wcmmq_icon-heart-filled"></i>
                        Get Premium Offer
                    </a>
                <?php }else if($license_direct){ ?>
                    <a class="wcmmq-btn wcmmq-has-icon" 
                        href="<?php esc_attr( admin_url() ) ?>admin.php?page=wcmmq-license">
                        <span><i class=" wcmmq_icon-heart-1"></i></span>
                        License
                    </a>
                <?php } ?>
                
                <a class="wcmmq-button reset" 
                    href="https://codeastrology.com/min-max-quantity/documentation/" 
                    target="_blank">
                    <i class="wcmmq_icon-note"></i>Documentation
                </a>
                
                <!-- <button class="wcmmq-btn"><span><i class="wcmmq_icon-cart"></i></span> Save Chabnge</button> -->
            </div>
        </div>
    </div>
</div>