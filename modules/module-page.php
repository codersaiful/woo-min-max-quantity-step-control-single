<?php 

/**
 * ei file ta ekhon page load class er madhome include kora hoyeche.
 * so here main class is:
 * WC_MMQ\Page_Loader class
 */

if( isset( $_POST['ca-module-submit'] ) && filter_input_array(INPUT_POST) ){
    $data = filter_input_array( INPUT_POST );
    $values = $data['data'] ?? array();
    $this->module_controller->update($values);

}
$module_datas = $this->module_controller->modules;
$modules_list = $this->module_controller->get_module_list();
// var_dump($modules_list);
?>
<div class="wrap wcmmq_wrap wcmmq-content">
    <h1 class="wp-heading "></h1>

    <div class="fieldwrap">


    <div class="wcmmq-section-panel no-background">
        <a class="wcmmq-btn wcmmq-has-icon" href="https://codeastrology.com/my-support" target="_blank"><span><i class="wcmmq_icon-ok"></i></span>Support</a>
        
        <!-- <a class="wcmmq-btn reset wcmmq-has-icon" href="https://codeastrology.com/my-support" target="_blank"><span><i class="wcmmq_icon-ok"></i></span>Link</a>
        <a class="wcmmq-btn round wcmmq-has-icon" href="#"><span><i class="wcmmq_icon-ok"></i></span>Link</a> -->
        <a class="wcmmq-btn reset round wcmmq-has-icon" href="https://profiles.wordpress.org/codersaiful/#content-plugins" target="_blank"><span><i class="wcmmq_icon-ok"></i></span>Plugins</a>
    </div>
<form action="" method="POST" id="wcmmq-main-configuration-form">

    <div class="wcmmq-section-panel module-page-wrapper" id="module-page-wrapper">
        <table class="wcmmq-table universal-setting">
            <thead>
                <tr>
                    <th class="wcmmq-inside">
                        <div class="wcmmq-table-header-inside">
                            <h3><?php echo esc_html__( 'Module Switcher', 'wcmmq' ); ?></h3>
                        </div>
                        
                    </th>
                    <th>
                    <div class="wcmmq-table-header-right-side"></div>
                    </th>
                </tr>
            </thead>

            <tbody>

                <?php 
                foreach( $modules_list as $key=>$modl ){

                    $key_name = $modl['key'] ?? '';
                    $name = $modl['name'] ?? '';
                    $desc = $modl['desc'] ?? '';
                    $status = $modl['status'] ?? '';
                    $checkbox = $status == 'off' ? '' : 'checked';
                ?>
                <tr>
                    <td>
                        <div class="wcmmq-form-control">
                            <div class="form-label col-lg-6">
                                <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $name ); ?></label>
                            </div>
                            <div class="form-field col-lg-6">

                                <label class="switch">
                                    <input 
                                    value="on"
                                    name="data[<?php echo esc_attr( $key ); ?>]"
                                    <?php echo esc_attr( $checkbox ); ?>
                                    type="checkbox" id="<?php echo esc_attr( $key ); ?>">
                                    <div class="slider round"><!--ADDED HTML -->
                                        <span class="on"><?php echo esc_html__('ON','wcmmq');?></span><span class="off"> <?php echo esc_html__('OFF','wcmmq');?></span><!--END-->
                                    </div>
                                </label>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="wcmmq-form-info">
                            <p><?php echo esc_html( $desc ); ?></p>
                        </div> 
                    </td>
                </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
    </div>


    <div class="wcmmq-section-panel no-background wcmmq-full-form-submit-wrapper">
                    
        <button name="ca-module-submit" type="submit"
            class="wcmmq-btn wcmmq-has-icon wcmmq-submit-button configure_submit">
            <span><i class="wcmmq_icon-floppy"></i></span>
            <strong class="form-submit-text">
            <?php echo esc_html__('Save Change','wcmmq');?>
            </strong>
        </button>
        
    </div>


</form>

<div class="wcmmq-section-panel supported-terms wcmmq-recomendation-area" id="wcmmq-recomendation-area">
    <table class="wcmmq-table universal-setting">
        <thead>
            <tr>
                <th class="wcmmq-inside">
                    <div class="wcmmq-table-header-inside">
                        <h3><?php echo esc_html__('Recommendation Area', 'wpt'); ?> <small class="wcmmq-small-title">To increase Sale</small></h3>
                    </div>

                </th>
                <th>
                    <div class="wcmmq-table-header-right-side"></div>
                </th>
            </tr>
        </thead>

        <tbody>
        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-12">
                    <?php do_action( 'wcmmq_plugin_recommend_here' ); ?>
                    </div>
                    <div class="form-label col-lg-12">
                        <?php wcmmq_submit_issue_link(); ?>
                    </div>
                </div>
            </td>
            <td>
                <div class="wqpmb-form-info">
                    
                    <?php wcmmq_social_links(); ?>
                    <p>Highly Recommeded these plugin. Which will help you to increase your WooCommerce sale.</p>
                </div> 
            </td>
        </tr>
        </tbody>
    </table>

</div> <!--/.wcmmq-recomendation-area -->



    </div> <!-- ./fieldwrap -->
</div><!-- ./wrap wcmmq_wrap wcmmq-content -->