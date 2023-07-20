<?php
    
$term_lists_temp = get_object_taxonomies('product','objects');

$wcmmq_all_terms= apply_filters( 'wcmmq_all_terms', false, $term_lists_temp, $saved_data );
if( $wcmmq_all_terms ){
    $term_lists = $term_lists_temp;
}else{
    $term_lists['product_cat']=$term_lists_temp['product_cat'];
}

$supported_terms = isset( $saved_data['supported_terms'] ) ?$saved_data['supported_terms'] : array( 'product_cat' );
$ourTermList = $select_option = false;
if( is_array( $term_lists ) && count( $term_lists ) > 0 ){
    foreach( $term_lists as $trm_key => $trm_object ){
        $selected =  ( !$supported_terms && $trm_key == 'product_cat' ) || ( is_array( $supported_terms ) && in_array( $trm_key, $supported_terms ) ) ? 'selected' : false;
        //( !$supported_terms && $trm_key == 'product_cat' ) ||
        //var_dump($trm_key,$selected);
        if( $trm_object->labels->singular_name == 'Tag' && $trm_key !== 'product_tag' ){
            $value = $trm_key;
            $select_option .= "<option value='" . esc_attr( $trm_key ) . "' " . esc_attr( $selected ) . ">" . $trm_key . "</option>";
        }else{
            $value = $trm_object->labels->singular_name;
            $select_option .= "<option value='" . esc_attr( $trm_key ) . "' " . esc_attr( $selected ) . ">" . $trm_object->labels->singular_name . "</option>";
        }
        if( $selected ){
        $ourTermList[$trm_key] = $value; 
        }
    }
}

?>

<table class="wcmmq-table supported-terms">
    <thead>
        <tr>
            <th class="wcmmq-inside">
                <div class="wcmmq-table-header-inside">
                    <h3><?php echo esc_html__( 'Supported Terms', 'wcmmq' ); ?></h3>
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
                    <div class="form-label col-lg-6">
                        <label for=""><?php echo esc_html__('Choose Terms','wcmmq');?></label>
                    </div>
                    <div class="form-field col-lg-6">
                        <select name="data[supported_terms][]" data-name="supported_terms" class="ua_input_select" id="wcmmq_supported_terms" multiple>
                        <?php
                            echo $select_option;
                        ?>
                        </select>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-conditions-to-a-specific-category/'); ?>
                <p class="wcmmq_terms_promotion">
                <?php 
                    if( ! defined( 'WC_MMQ_PRO_VERSION' ) ){
                    echo esc_html__('For Mulitple Terms,','wcmmq');?> <a href="https://codeastrology.com/min-max-quantity/pricing/"><?php echo esc_html__('Upgrade to PRO','wcmmq');?></a>    
                <?php
                    };
                ?>
                </p>
                </div> 
            </td>
        </tr>
    </tbody>
</table>   