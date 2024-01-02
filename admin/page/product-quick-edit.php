<?php
use WC_MMQ\Admin\Adm_Inc\Plugin_Installer;
use Product_Stock_Sync\App\Handle\Quick_Table;

?>
<div class="wrap wcmmq_wrap wcmmq-content">

    <h1 class="wp-heading "></h1>
    <div class="fieldwrap">

    <?php
    if( class_exists( 'Product_Stock_Sync\App\Handle\Quick_Table' ) ){
        $Quick_Table = Quick_Table::init();
        $Quick_Table->display_table_full();
    }else{
    
        
        $Plugin_Installer = Plugin_Installer::init();


    /**
     * ekhane amra debo jate
     * install na thakle free version install dite pare
     */
    $image_url = $this->assets_url . 'images/features/Qucck-Edit-Table.jpg';
    ?>
    <div class="wcmmq-section-panel no-background wcmmq-clearfix">
    <div class="wcmmq-section-panel no-background quick-edit-section-wrapper">
        <div class="wcmmq-section-panel wcmmq-quick-edit-section" id="wcmmq-quick-edit-section" data-icon="wcmmq_icon-home">

            <div class="wcmmq-qes-wrapper">
                <div class="plugin-install-wrapper">
                <form method="post" action="">
                    <?php wp_nonce_field('pssg_install_plugin_nonce', 'pssg_install_plugin_nonce'); ?>
                    <input type="submit" name="install_plugin" value="Install Required Plugin" class="wcmmq-btn"/>
                </form>
                </div>
                <img src="<?php echo esc_attr( $image_url ); ?>" alt="Quick Edit Table Image">
            </div>

        </div>
    </div>
    </div>
    <?php   
    }
    
    ?>

        
    </div>
</div>