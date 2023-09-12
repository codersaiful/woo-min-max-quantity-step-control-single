<div class="wcmmq-section-panel">
<table class="wcmmq-table universal-setting">
    <thead>
        <tr class="user_can_not_edit ">
            <th class="wcmmq-inside">
                <div class="wcmmq-table-header-inside">
                    <h3>Quantity Prefix/Suffix</h3>
                </div>
                
            </th>
            <th>
            <div class="wcmmq-table-header-right-side"></div>
            </th>
        </tr>
    </thead>
    <tbody>

        <tr class="user_can_not_edit ">
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="wcmmq-prefix-quantity">Prefix of Quantity</label>
                    </div>
                    <div class="form-field col-lg-6">
                    <?php
                            $thsis_value = '';
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>

                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    
                </div> 
            </td>
        </tr>

        <tr class="user_can_not_edit ">
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="wcmmq-sufix-quantity">Suffix of Quantity</label>
                    </div>
                    <div class="form-field col-lg-6">
                    <?php
                            $thsis_value = '';
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>

                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    
                </div> 
            </td>
        </tr>
    </tbody>
</table>

</div>
<div class="wcmmq-section-panel">
    <table class="wcmmq-table cart-page-condition">
        <thead>
            <tr>
                <th class="wcmmq-inside">
                    <div class="wcmmq-table-header-inside">
                        <h3>Cart Page Conditions</h3>
                    </div>
                </th>
                <th>
                    <div class="wcmmq-table-header-right-side"></div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="user_can_not_edit extra-divider">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Quantity Limit</label>
                        </div>
                        <div class="form-field col-lg-6">
                            <div class="inside-field-collection">
                                <div class="col-lg-12 inside-form-field form-field">
                                    <label for="cart_quantity_min">Min (Optional)</label>  
                                    <input type="number" name="data_save[cart_quantity_min]" id="cart_quantity_min" value="" placeholder="Minimum" quantity="">
                                </div>
                                <div class="col-lg-12 inside-form-field form-field">
                                    <label for="cart_quantity_max">Max(Optional)</label>
                                    <input type="number" name="data_save[cart_quantity_max]" id="cart_quantity_max" value="" placeholder="Maximum" quantity="">
                                </div>
                                <div class="col-lg-12 inside-form-field form-field">
                                    <label for="cart_quantity_step">Step(Optional)</label>
                                    <input type="number" name="data_save[cart_quantity_step]" id="cart_quantity_step" value="" placeholder="Step" quantity=""> 
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        <a href="https://codeastrology.com/min-max-quantity/set-conditions-on-cart-page/" target="_blank" class="wpt-doc-lick"><i class="wcmmq_icon-help-circled-alt"></i>Helper doc</a>
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit extra-divider">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label>Price Limit</label>
                        </div>
                        <div class="form-field col-lg-6">
                            <div class="inside-field-collection">
                                <div class="col-lg-12 inside-form-field form-field">
                                    <label for="cart_price_min">Min(Optional)</label>
                                    <input type="number" name="data_save[cart_price_min]" id="cart_price_min" value="" placeholder="Minimum" price="">
                                </div>
                                <div class="col-lg-12 inside-form-field form-field">
                                    <label for="cart_price_max">Max(Optional)</label>
                                    <input type="number" name="data_save[cart_price_max]" id="cart_price_max" value="" placeholder="Maximum" price="">
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        <a href="https://codeastrology.com/min-max-quantity/set-conditions-on-cart-page/" target="_blank" class="wpt-doc-lick"><i class="wcmmq_icon-help-circled-alt"></i>Helper doc</a>
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="cart_product_exclude">Product Exclude</label>
                        </div>
                        <div class="form-field col-lg-6">
                            <input type="text" name="data_save[cart_product_exclude]" id="cart_product_exclude" value="" placeholder="Input" product="" ids.eg:="" 45,46,50="">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        <a href="https://codeastrology.com/min-max-quantity/exclude-include-products-on-cart-page/" target="_blank" class="wpt-doc-lick"><i class="wcmmq_icon-help-circled-alt"></i>Helper doc</a>
                        <p>Insert Products IDs. Use a comma as a separator. (Example: 45,84,5).  Cart conditions will not apply to those products</p>
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="cart_product_include">Product Include</label>
                        </div>
                        <div class="form-field col-lg-6">
                            <input type="text" name="data_save[cart_product_include]" id="cart_product_include" value="" placeholder="Input" product="" ids.eg:="" 45,46,50="">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        <a href="https://codeastrology.com/min-max-quantity/exclude-include-products-on-cart-page/" target="_blank" class="wpt-doc-lick"><i class="wcmmq_icon-help-circled-alt"></i>Helper doc</a>
                        <p>Insert Products IDs. Use a comma as a separator. (Example: 45,84,5).  Cart conditions will apply only those products</p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<div class="wcmmq-section-panel message">
    <table class="wcmmq-table message">
        <thead>
            <tr>
                <th class="wcmmq-inside">
                    <div class="wcmmq-table-header-inside">
                        <h3>Cart Page Notices</h3>
                    </div>
                </th>
                <th>
                    <div class="wcmmq-table-header-right-side"></div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Cart Minimum Price Validation Message</label>
                        </div>
                        <div class="form-field col-lg-6">
                            <?php
                            $thsis_value = 'Your cart total amount must be equal to or more of [cart_min_price]';
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        Available shortcode: [cart_min_price]                                
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Cart Maximum Price Validation Message</label>
                        </div>
                        <?php
                            $thsis_value = 'Your cart total amount must be equal to or less than [cart_max_price]';
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        Available shortcode: [cart_min_quantity]                                
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Cart Minimum Quantity Validation Message</label>
                        </div>
                        <div class="form-field col-lg-6">
                            <?php
                                $thsis_value = "Your cart total amount must be equal to or less than [cart_max_price]";
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        Available shortcode: [cart_min_quantity]                                
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Cart Maximum Quantity Validation Message</label>
                        </div>
                        <div class="form-field col-lg-6">
                        <?php
                            $thsis_value = "Your cart item's total quantity must be equal to or more than [cart_min_quantity]";  
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        Available shortcode: [cart_max_quantity]                                
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Cart Step Quantity Validation Message</label>
                        </div>
                        <div class="form-field col-lg-6">
                        <?php
                            $thsis_value = 'Please enter a valid value. Value should be multiplier of [step_quantity]';
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        Available shortcode: [step_quantity]                                
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Variation Total Maximum Quantity Message</label>
                        </div>
                        <div class="form-field col-lg-6">
                        <?php
                            $thsis_value = 'Maximum variation quantity total of "[product_name]" should be or less then [vari_total_max_qty]';
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        Available shortcode: [vari_total_max_qty], [product_name]                                
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Variation Total Minimum Quantity Message</label>
                        </div>
                        <div class="form-field col-lg-6">
                        <?php
                            $thsis_value = 'Minimum variation quantity total of "[product_name]" should be or greater then [vari_total_min_qty]';
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        Available shortcode: [vari_total_min_qty], [product_name]                                
                    </div>
                </td>
            </tr>
            <tr class="user_can_not_edit ">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="">Variation Total Count Message</label>
                        </div>
                        <div class="form-field col-lg-6">
                        <?php
                            $thsis_value = 'Maximum variation count total of "[product_name]" should be or less then [vari_count_total]';
                            $f_key_name = 'wcmmq_' . rand(123,456);
                            $settings = array(
                                'textarea_name'     => $f_key_name,
                                'textarea_rows'     => 3,
                                'teeny'             => true,
                                );
                            wp_editor( esc_attr( $thsis_value ), $f_key_name, $settings ); 
                            ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        Available shortcode: [vari_count_total], [product_name]                                
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>