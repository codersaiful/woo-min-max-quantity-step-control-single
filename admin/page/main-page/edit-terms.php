
<div class="wcmmq-terms-wrapper">
    <table class="wcmmq-table edit-terms">
        <thead>
            <tr>
                <th class="wcmmq-inside">
                    <div class="wcmmq-table-header-inside">
                        <h3><?php echo esc_html__('Edit Terms','wcmmq');?></h3>
                    </div>
                    
                </th>
                <th>
                    <div class="wcmmq-table-header-right-side"></div>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php

        /**
         * Automatically display all terms in Set on Terrms
         */
        $support_all_terms = apply_filters( 'wcmmq_display_all_terms', false, $saved_data );
        if( $support_all_terms ){
            $term_lists = get_object_taxonomies('product','objects');
            //var_dump($term_lists);
            $ourTermList = false;
            foreach( $term_lists as $trm_key => $trm_object ){
                if( $trm_object->labels->singular_name == 'Tag' && $trm_key !== 'product_tag' ){
                    $ourTermList[$trm_key] = $trm_key;
                }else{
                    $ourTermList[$trm_key] = $trm_object->labels->singular_name;
                }
            }
        }

        $term_lists = apply_filters( 'wcmmq_terms_list', $ourTermList, $saved_data );

        $args = array(
            'hide_empty'    => false, 
            'orderby'       => 'count',
            'order'         => 'DESC',
        );
        $_term_lists = isset( $saved_data['terms'] ) && is_array( $saved_data['terms'] ) ? array_merge( $saved_data['terms'], $term_lists ) : $term_lists;

        foreach( $_term_lists as $key => $each ){
            $term_key = $key;
            $term_name = !empty( $term_lists[$key] ) ? $term_lists[$key] : $key;

            $term_obj = get_terms( $term_key, $args );

            $selected_term_ids = isset( $saved_data['terms'][$term_key] ) && !empty( $saved_data['terms'][$term_key] ) ? $saved_data['terms'][$term_key] : false;
            $selected_term_ids = wcmmq_term_ids_wpml( $selected_term_ids, $key );

            include WC_MMQ_BASE_DIR . 'admin/includes/terms_condition.php';
        }


        ?>  
        </tbody>
    </table>
                      
</div><!-- /.wcmmq-terms-wrapper -->                


<script>
jQuery(document).ready(function($){
    $(document).on('click','.add_terms_button', function(e){
        
        e.preventDefault();
        var term_key = $(this).attr('data-term_key');
        var id = $('.wcmmq_select_terms.' + term_key).val();
            var term_name  = $('.wcmmq_select_terms.' + term_key + ' option[value="' + id + '"]').text();
        if( $('#wcmmq_terms_' + term_key + '_' + id).length > 0 ){
            alert("Already Added");
            return;
        }
        var html = '';
        var td, tdC, th, thC, tr, trC;
        td = '<td>';
        tdC = '</td>';
        th = '<th>';
        thC = '</th>'
        tr = '<tr>';
        trC = '</tr>';
        html += '<div id="wcmmq_terms_' + term_key + '_' + id + '" class="wcmmq_each_terms wcmmq-each-term-temp"  data-term_key="' + term_key + '" data-term_id="' + id + '">\n\
                <ul class="wcmmq_each_terms_header" data-target="term_table_' + id + '">\n\
                    <li class="label">' + term_name + '<small>' + term_key + '</small></li>\n\
                    <li class="edit" data-target="term_table_' + id + '"><i class="wcmmq_icon-dot-3"></i></li>\n\
                    <li class="delete"><i class="wcmmq_icon-trash-empty"></i></li>\n\
                </ul>\n\
                <div class="product_cat">';
        html += '<table id="term_table_' + id + '">';
        html += tr + th; 
        html += '<label><?php echo esc_html__( 'Minimum Quantity', 'wcmmq' ); ?></label>';
        html += thC + td;
        html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_min]" value=""  type="number" step=any>';
        html += tdC + trC + tr + th; 
        html += '<label><?php echo esc_html__( 'Maximum Quantity', 'wcmmq' ); ?></label>';
        html += thC + td;
        html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_max]" value=""  type="number" step=any>';
        html += tdC + trC + tr + th;
        html += '<label><?php echo esc_html__( 'Step Quantity', 'wcmmq' ); ?></label>';
        html += thC + td;
        html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_step]" value=""  type="number" step=any>';
        html += tdC + trC;

        <?php
        $default_qty = apply_filters( 'wcmmq_default_qty_option', false, $saved_data );
        if( $default_qty ){
        ?> 
        html += tr + th;
        html += '<label><?php echo esc_html__( 'Default Quantity', 'wcmmq' ); ?></label>';
        html += thC + td;
        html += '<input class="ua_input" name="data[terms]['+ term_key +']['+ id +'][_default]" value=""  type="number" step=any>';
        html += tdC + trC;
        <?php } ?>
        html += '</table>';
        html += '</div></div>';
        $('.wcmmq_terms_wrapper.term_wrapper_' + term_key).prepend(html);
        $('#wcmmq_terms_' + term_key + '_' + id).fadeIn('slow');
    });
    
    $(document).on('click','ul.wcmmq_each_terms_header',function(){
        var table_id = $(this).attr('data-target');
        $('#' + table_id).fadeToggle();
    });
        
    // delete from list
    $('body').on('click', '.delete', function(){
        //e.preventDefault();
        var thisParentEl = $(this).parents('.wcmmq_each_terms');
        thisParentEl.fadeOut('medium',function(){
            thisParentEl.remove();
        });
    });
    
    $( ".wcmmq_terms_wrapper, .wcmmq-terms-wrapper" ).sortable({
        handle:this,//'.ultratable-handle'//this //.ultratable-handle this is handle class selector , if need '.ultratable-handle',
    });
    
        //woocommerce_page_wcmmq_min_max_step 
    function wcmmqSelectItem(target, id) { // refactored this a bit, don't pay attention to this being a function
        var option = $(target).children('[value='+id+']');
        option.detach();
        $(target).append(option).change();
    }
    $('.wcmmq_config_form select').select2();
    $('.wcmmq_config_form select').on('select2:select', function(e){
    wcmmqSelectItem(e.target, e.params.data.id);
    });    
});
</script>