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

    </div> <!-- ./fieldwrap -->
</div><!-- ./wrap wcmmq_wrap wcmmq-content -->