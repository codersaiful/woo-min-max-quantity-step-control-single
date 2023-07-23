<?php
$min_max_img = WC_MMQ_BASE_URL . 'assets/images/brand/social/min-max.png';
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
                    <h2 class="wcmmq-ntitle"><?php _e("Manage and Settings", "wcmmq");?></h2>
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
                <?php }else{ ?>
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