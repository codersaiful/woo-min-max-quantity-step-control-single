<table class="wcmmq-table universal-setting">
    <thead>
        <tr>
            <th class="wcmmq-inside">
                <div class="wcmmq-table-header-inside">
                    <h3><?php echo esc_html__( 'Support & Tracker', 'wcmmq' ); ?></h3>
                </div>
                
            </th>
            <th>
            <div class="wcmmq-table-header-right-side"></div>
                <p class="live-support">Customer live support system - on or off.</p>
            </th>
        </tr>
    </thead>

    <tbody>
        
        

        <!-- 
        * Will add quantity box on archive pages
        * @ since 3.6.0
        * @ Author Fazle Bari 
        -->
        <?php $live_support = isset( $saved_data['disable_live_support' ] ) && $saved_data['disable_live_support' ] == '1' ? 'checked' : false; ?>
        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="_disable_live_support"><?php echo esc_html__('Live Support','wcmmq');?></label>
                    </div>
                    <div class="form-field col-lg-6">
                        <label class="switch reverse">
                            <input value="1" name="data[disable_live_support]"
                                <?php echo $live_support; /* finding checked or null */ ?> type="checkbox" id="_disable_live_support">
                            <div class="slider round"><!--ADDED HTML -->
                                <span class="on"><?php echo esc_html__('ON','wcmmq');?></span><span class="off"> <?php echo esc_html__('OFF','wcmmq');?></span><!--END-->
                            </div>
                        </label>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    <?php wcmmq_doc_link('https://codeastrology.com/my-support', 'Customer Support'); ?>
                    
                </div> 
            </td>
        </tr>
        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="_disable_live_support"><?php echo esc_html__('Important Link','wcmmq');?></label>
                    </div>
                    <div class="form-field col-lg-6">
                        <div class="wcmmq-important-link-area">
                            <a class="wcmmq-btn reset wcmmq-has-icon wcmmq-btn-tiny" 
                              href="https://codeastrology.com/min-max-quantity/"
                              title="Pro Feature and Min Max Control Home Page"
                              target="_blank">
                                <span><i class="wcmmq_icon-globe-inv"></i></span>    
                                Web                       
                            </a>
                            <a class="wcmmq-btn wcmmq-has-icon wcmmq-btn-tiny" 
                              href="https://github.com/codersaiful/woo-min-max-quantity-step-control-single"
                              title="Github Repository of Min Man Control Free version"
                              target="_blank">
                                <span><i class="wcmmq_icon-github-circled"></i></span>    
                                Github Repo                       
                            </a>
                            <a class="wcmmq-btn wcmmq-has-icon wcmmq-btn-tiny"
                              href="https://github.com/codersaiful/woo-min-max-quantity-step-control-single/issues/new"
                              title="Submit your issue and you can request for a feature"
                              target="_blank">
                                <span><i class="wcmmq_icon-github"></i></span>    
                                Submit Issue                       
                            </a>
                            <a class="wcmmq-btn wcmmq-has-icon wcmmq-btn-tiny"
                              href="https://www.trustpilot.com/review/codeastrology.com"
                              target="_blank">
                                <span><i class="wcmmq_icon-star-filled"></i></span>    
                                Review                       
                            </a>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">

                </div> 
            </td>
        </tr>

        <?php $tracker = isset( $saved_data['tracker' ] ) && $saved_data['tracker' ] == '1' ? 'checked' : false; ?>
        <tr style="display: none;">
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="_tracker" title="Help Us to Improve plugin based on user data."><?php echo esc_html__('Tracker','wcmmq');?><i class="wcmmq-optional">Optional</i></label>
                    </div>
                    <div class="form-field col-lg-6">
                        
                        <label class="switch">
                            <input value="1" name="data[tracker]"
                                <?php echo $tracker; /* finding checked or null */ ?> type="checkbox" id="_tracker">
                            <div class="slider round"><!--ADDED HTML -->
                                <span class="on"><?php echo esc_html__('ON','wcmmq');?></span><span class="off"> <?php echo esc_html__('OFF','wcmmq');?></span><!--END-->
                            </div>
                        </label>
                        <p class="warning-alert">
                            Tracker will send some basic date to Plugin Author. such as: WordPress Version, MySQL Version,WooCommerce Version, site title, Min Max Plugin version, Theme Name etc.
                            <i>If you don't want to share these info, Off this option.</i> We don't sale your data, We use your data as servey for our plugin improve and update. 
                        </p>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    <p>
                        <b>Help us</b> to improve our plugin by proding your basic data. 
                    </p>
                    
                </div> 
            </td>
        </tr>
        
    </tbody>

    
</table>