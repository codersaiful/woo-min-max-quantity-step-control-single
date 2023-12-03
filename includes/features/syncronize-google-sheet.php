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

        $controller = new Min_Max_Controller();
        $columns[$controller->min_quantity] = [
            'type' => 'cf',
            'title' => 'Min Qty',
        ];
        $columns[$controller->max_quantity] = [
            'type' => 'cf',
            'title' => 'Max Qty',
        ];
        $columns[$controller->product_step] = [
            'type' => 'cf',
            'title' => 'Product Step',
        ];

        return $columns;
    }
}