<?php
namespace WC_MMQ\Includes\Features;

use WC_MMQ\Includes\Min_Max_Controller;

class Syncronize_Google_Sheet{

    public function run()
    {
        
        if( ! class_exists('PSSG_Init') ) return;
        
        add_filter('pssg_products_columns', [$this,'add_columns_on_sheet']);
    }

    public function add_columns_on_sheet( $columns )
    {

        $controller = Min_Max_Controller::init();
        $columns[$controller->min_quantity] = [
            'type' => 'cf',
            'title' => __( 'Min Qty', 'wcmmq' ),
        ];
        $columns[$controller->max_quantity] = [
            'type' => 'cf',
            'title' => __( 'Max Qty', 'wcmmq' ),
        ];
        $columns[$controller->product_step] = [
            'type' => 'cf',
            'title' => __( 'Product Step', 'wcmmq' ),
        ];

        return $columns;
    }
}