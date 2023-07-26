<table class="wcmmq-table universal-setting">
    <thead>
        <tr>
            <th class="wcmmq-inside">
                <div class="wcmmq-table-header-inside">
                    <h3><?php echo esc_html__( 'Live Support', 'wcmmq' ); ?></h3>
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
                            <a class="wcmmq-btn wcmmq-btn-small wcmmq-has-icon">
                                <span><i class="wcmmq_icon-plus"></i></span>    
                                Github Repo                       
                            </a>
                            <a class="wcmmq-btn wcmmq-btn-small wcmmq-has-icon">
                                <span><i class="wcmmq_icon-plus"></i></span>    
                                Submit Issue                       
                            </a>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    <?php wcmmq_doc_link('https://codeastrology.com/my-support', 'Customer Support'); ?>
                    
                </div> 
            </td>
        </tr>

        
    </tbody>

    
</table>