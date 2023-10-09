<?php
    
$term_lists_temp = get_object_taxonomies('product','objects');
                    
// var_dump($term_lists_temp);
$wcmmq_all_terms= apply_filters( 'wcmmq_all_terms', false, $term_lists_temp, $saved_data );
if( $wcmmq_all_terms ){
    $term_lists = $term_lists_temp;
}else{
    $term_lists['product_cat']=$term_lists_temp['product_cat'];
}

/**
 * We will remove Product Attribute, like: size, color or any other product attribute
 * from Terms support list
 * 
 * asole amra jevabe taxonomy pai:
_______________________________________
'pa_color' => 
    object(WP_Taxonomy)[381]
      public 'name' => string 'pa_color' (length=8)
      public 'label' => string 'Product Color' (length=13)
      public 'labels' => 
        object(stdClass)[376]
          public 'name' => string 'Product Color' (length=13)
          public 'singular_name' => string 'Color' (length=5)
          public 'search_items' => string 'Search Color' (length=12)
          public 'popular_items' => string 'Popular Tags' (length=12)
          public 'all_items' => string 'All Color' (length=9)
          public 'parent_item' => string 'Parent Color' (length=12)
          public 'parent_item_colon' => string 'Parent Color:' (length=13)
          public 'name_field_description' => string 'The name is how it appears on your site.' (length=40)
          public 'slug_field_description' => string 'The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.' (length=140)
          public 'parent_field_description' => null
          public 'desc_field_description' => string 'The description is not prominent by default; however, some themes may show it.' (length=78)
          public 'edit_item' => string 'Edit Color' (length=10)
          public 'view_item' => string 'View Tag' (length=8)
          public 'update_item' => string 'Update Color' (length=12)
          public 'add_new_item' => string 'Add new Color' (length=13)
          public 'new_item_name' => string 'New Color' (length=9)
          public 'separate_items_with_commas' => string 'Separate tags with commas' (length=25)
          public 'add_or_remove_items' => string 'Add or remove tags' (length=18)
          public 'choose_from_most_used' => string 'Choose from the most used tags' (length=30)
          public 'not_found' => string 'No &quot;Color&quot; found' (length=26)
          public 'no_terms' => string 'No tags' (length=7)
          public 'filter_by_item' => null
          public 'items_list_navigation' => string 'Tags list navigation' (length=20)
          public 'items_list' => string 'Tags list' (length=9)
          public 'most_used' => string 'Most Used' (length=9)
          public 'back_to_items' => string '&larr; Back to "Color" attributes' (length=33)
          public 'item_link' => string 'Tag Link' (length=8)
          public 'item_link_description' => string 'A link to a tag.' (length=16)
          public 'menu_name' => string 'Product Color' (length=13)
          public 'name_admin_bar' => string 'Color' (length=5)
          public 'archives' => string 'All Color' (length=9)
      public 'description' => string '' (length=0)
      public 'public' => boolean false
      public 'publicly_queryable' => boolean false
      public 'hierarchical' => boolean false
      public 'show_ui' => boolean true
      public 'show_in_menu' => boolean false
      public 'show_in_nav_menus' => boolean false
      public 'show_tagcloud' => boolean true
      public 'show_in_quick_edit' => boolean false
      public 'show_admin_column' => boolean false
      public 'meta_box_cb' => boolean false
      public 'meta_box_sanitize_cb' => string 'taxonomy_meta_box_sanitize_cb_input' (length=35)
      public 'object_type' => 
        array (size=1)
          0 => string 'product' (length=7)
      public 'cap' => 
        object(stdClass)[380]
          public 'manage_terms' => string 'manage_product_terms' (length=20)
          public 'edit_terms' => string 'edit_product_terms' (length=18)
          public 'delete_terms' => string 'delete_product_terms' (length=20)
          public 'assign_terms' => string 'assign_product_terms' (length=20)
      public 'rewrite' => boolean false
      public 'query_var' => boolean false
      public 'update_count_callback' => string '_update_post_term_count' (length=23)
      public 'show_in_rest' => boolean false
      public 'rest_base' => boolean false
      public 'rest_namespace' => boolean false
      public 'rest_controller_class' => boolean false
      public 'rest_controller' => null
      public 'default_term' => null
      public 'sort' => boolean false
      public 'args' => null
      public '_builtin' => boolean false
_______________________________________
 * 
 * ekhane Taxonomy_Object->labels->back_to_items a string thake erokom "'&larr; Back to "Color" attributes'"
 * ekhan theke 'attributes' string er strpost thakle amra seta bad debo.
 * ejonno ami korechi. empty hole true, mane position paoya jayni. r jayni manei 'attributes' string ta nei.
 * 
 */
$term_lists = array_filter($term_lists,function($kkk){
    $parenttt = $kkk->labels->back_to_items;
    return empty(strpos($parenttt, 'attributes'));
});
$supported_terms = isset( $saved_data['supported_terms'] ) ?$saved_data['supported_terms'] : array( 'product_cat' );
$ourTermList = $select_option = false;
if( is_array( $term_lists ) && count( $term_lists ) > 0 ){
    foreach( $term_lists as $trm_key => $trm_object ){
        // var_dump($trm_object->labels->back_to_items);
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
                    <div class="form-field col-lg-6" >
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
                <p>Set Taxonomy wise miminum, maximum and step quantity.</p>
                <?php if( ! defined( 'WC_MMQ_PRO_VERSION' ) ){ ?>
                <p class="wcmmq_terms_promotion wcmmq-input-decimal-msg-free">
                <?php 
                    echo esc_html__('For Mulitple Terms, Such: Category, Tag, Color, Size or any other taxonomy. Need Pro version. ','wcmmq');?> <a href="https://codeastrology.com/min-max-quantity/pricing/"><?php echo esc_html__('Upgrade to PRO','wcmmq');?></a>    
                </p>
                <?php
                    };
                ?>
                </div> 
            </td>
        </tr>
    </tbody>
</table>   