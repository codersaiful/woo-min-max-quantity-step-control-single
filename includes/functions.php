<?php 

/**
 * Getting term list new/agin generate based on
 * wpml
 * 
 * asole wpml er madhome category/taxonomy asle ter number alada hoy
 * sei jonno sei onusare ID ta ber korar jonno wpml_object_id filter ta use korechi
 *
 * @param Array $terms_data
 * @return Array
 */
function wcmmq_tems_based_wpml( $terms_data ){

    $temp_term = array();
    foreach( $terms_data as $key=>$e_temp ){
        
        foreach( $e_temp as $k=>$val ){
            unset($e_temp[$k]);
            $id = apply_filters( 'wpml_object_id', $k, $key, TRUE);
            $e_temp[$id] =$val;
        }
        $temp_term[$key] = $e_temp;
    }

    return $temp_term;
}

function wcmmq_get_term_data_wpml(){
    $terms_data = WC_MMQ::getOption( 'terms' );
    $terms_data = is_array( $terms_data ) ? $terms_data : array();
    return wcmmq_tems_based_wpml( $terms_data );
}