<div class="wcmmq-each-terms-wrapper">
    

    <table class="wcmmq_config_form">
        <tr>
            <th><label><?php echo sprintf( esc_html__( 'Choose a %s', 'wcmmq' ), $term_name ); ?></label></th>
            <td class="">

                <?php
                $options_item = '';
                if( is_array( $term_obj ) && count( $term_obj ) > 0 ){

                    foreach ( $term_obj as $terms ) {
                        $options_item .= "<option value='{$terms->term_id}' >{$terms->name} ({$terms->count})</option>";
                    }
                }

                if( !empty( $options_item ) ){
                ?>
                <select class="wcmmq_select_terms <?php echo esc_attr( $term_key ); ?> ua_select" id="wcmmq_term_ids">
                    <?php echo $options_item; ?>
                </select>
                <button data-term_key="<?php echo esc_attr( $term_key ); ?>" class="add_terms_button primary button button-primary">+ <?php echo esc_html__( 'Add Terms', 'wcmmq' ); ?></button>    
                <?php    
                }else{
                    echo "No item for {$term_name}";
                }
                ?>

            </td>
        </tr>
    </table>
    <div class="wcmmq_terms_wrapper term_wrapper_<?php echo esc_attr( $term_key ); ?>">
        <?php
            if( is_array( $selected_term_ids ) && count( $selected_term_ids ) > 0 ){

                foreach( $selected_term_ids as $trm_id => $minmaxsteps ){
                    $id = $trm_id;
                    $min = $minmaxsteps['_min'] ?? '';
                    $max = $minmaxsteps['_max'] ?? '';
                    $step = $minmaxsteps['_step'] ?? '';
                    $default = $minmaxsteps['_default'] ?? '';
                    ?>

        <div  id="wcmmq_terms_<?php echo esc_attr( $term_key . '_' .$id ); ?>" class="wcmmq_each_terms"  data-term_key="<?php echo esc_attr( $term_key ); ?>" data-term_id="<?php echo esc_attr( $id ); ?>">
        <ul class="wcmmq_each_terms_header" data-target="term_table_<?php echo esc_attr( $id ); ?>">
            <li class="label"><?php echo get_term( $id )->name; ?> (<?php echo esc_html( get_term( $id )->count ); ?>)<small><?php echo esc_html( $term_key ); ?></small></li>
            <li class="edit" data-target="term_table_<?php echo esc_attr( $id ); ?>"><?php echo esc_html__( 'Edit', 'wcmmq' ); ?></li>
            <li class="delete"><?php echo esc_html__( 'Delete', 'wcmmq' ); ?></li>
         </ul> 
            <table id="term_table_<?php echo esc_attr( $id ); ?>">
        <tr>
            <th>
                <label><?php echo esc_html__( 'Minimum Quantity', 'wcmmq' ); ?></label>
            </th>
            <td>
                <input class="ua_input" name="data[terms][<?php echo esc_attr( $term_key ); ?>][<?php echo esc_attr( $id ); ?>][_min]" 
                       value="<?php echo $minmaxsteps['_min']; ?>"  type="number" step=any>
            </td>
        </tr> 

        <tr>
            <th>
                <label><?php echo esc_html__( 'Maximum Quantity', 'wcmmq' ); ?></label>
            </th>
            <td>
                <input class="ua_input" name="data[terms][<?php echo esc_attr( $term_key ); ?>][<?php echo esc_attr( $id ); ?>][_max]" 
                       value="<?php echo $minmaxsteps['_max']; ?>"  type="number" step=any>
            </td>
        </tr> 

        <tr>
            <th>
                <label><?php echo esc_html__( 'Step Quantity', 'wcmmq' ); ?></label>
            </th>
            <td>
                <input class="ua_input" name="data[terms][<?php echo esc_attr( $term_key ); ?>][<?php echo esc_attr( $id ); ?>][_step]" 
                       value="<?php echo $minmaxsteps['_step']; ?>"  type="number" step=any>
            </td>
        </tr> 
        <?php
        $default_qty = apply_filters( 'wcmmq_default_qty_option', false, $saved_data );
        if( $default_qty ){
        ?>            
        <tr>
            <th>
                <label><?php echo esc_html__( 'Default Quantity', 'wcmmq' ); ?></label>
            </th>
            <td>
                <input class="ua_input" name="data[terms][<?php echo esc_attr( $term_key ); ?>][<?php echo esc_attr( $id ); ?>][_default]" 
                       value="<?php echo $minmaxsteps['_default']; ?>"  type="number" step=any>
            </td>
        </tr> 
        <?php } ?>
        <?php do_action( 'wcmmq_edit_terms_bottom', $term_key, $minmaxsteps, $trm_id ); ?>

    </table>
        </div>
         <?php

                }

            }
        ?>
    </div>
</div>