<?php
namespace WC_MMQ\Includes;

use WC_MMQ;
use WC_MMQ\Core\Base;

class Min_Max_Controller extends Base
{

    public $product_id;
    /**
     * It's need, only when cart page, otherwise it will null
     *
     * @var int
     */
    public $variation_id;

    //Important value
    public $min_value;
    public $max_value;
    public $step_value;
    public $stock_quantity;

    //Important key
    public $min_quantity = WC_MMQ_PREFIX . 'min_quantity';
    public $default_quantity = WC_MMQ_PREFIX . 'default_quantity';
    public $max_quantity = WC_MMQ_PREFIX . 'max_quantity';
    public $product_step = WC_MMQ_PREFIX . 'product_step';

    /**
     * It's the property of where the args is final
     * Actually if found args on any product, 
     * it will be 'sinle'
     *
     * @var string it's can be single, taxonomy, global
     */
    protected $where_args_on = 'global';

    public $is_pro = false;
    public $is_args_organized = false;

    public $input_args;
    public $term_data;
    public $options;
    public $product;


    public function __construct()
    {
        $this->is_pro = defined('WC_MMQ_PRO_VERSION');
        $this->options = WC_MMQ::getOptions();
        $this->term_data = $this->options['terms'] ?? null;
        
        if( ! empty( $this->term_data ) ){
            $this->term_data = wcmmq_tems_based_wpml( $this->term_data );
        }
        
        //Input box args setup and manage
        add_filter('woocommerce_loop_add_to_cart_args',[$this, 'set_input_args'], 9999, 2);
        add_filter('woocommerce_quantity_input_args',[$this, 'set_input_args'], 9999, 2);
        add_filter('woocommerce_available_variation',[$this, 'set_input_args'], 9999, 2);

        //validation setup
        add_filter('woocommerce_add_to_cart_validation', [$this, 'add_to_cart_validation'], 10, 2);

    }

    public function add_to_cart_validation( $bool,$product_id)
    {
        $this->product = wc_get_product( $product_id );
        $this->product_id = $product_id;

        $this->assignInputArg();
        

        
        // var_dump($args);
        
        $this->finalizeArgs();

        // var_dump($this);
        return $bool;
    }

    /**
     * In this method, I will System and manage
     * input's args, I mean: min max and step value
     * Based on products or from cate or from
     * Global settings.
     * 
     * ******************
     * PROTECTION
     * ******************
     * * IF ALREADY ARGS ORGANIZED, IF ALREADY ORGANIZED, NO NEED AGAIN ORGANIZE
     *
     * @return void
     * 
     * @todo cart page hole ba variation hole ki hobe ta thik kora hoyni
     * @todo location: line: 472 file: set_max_min_quantity.php clue: if( is_cart() ){
     */
    protected function assignInputArg()
    {
        // if( $this->is_args_organized) return true;
        if( ! $this->product) return;
        var_dump($this->product_id);
        $this->is_args_organized = true;
        $this->stock_quantity = $this->product->get_stock_quantity();
        //First check from single product and if it on single page
        $this->min_value = $this->getMeta( $this->min_quantity );
        $this->max_value = $this->getMeta( $this->max_quantity );
        $this->step_value = $this->getMeta( $this->product_step );
        
        //Return here if found in single
        if( ! empty( $this->min_value ) || ! empty( $this->min_value ) || ! empty( $this->min_value ) ){
            $this->where_args_on = 'single';
            return true;
        }elseif( empty( $this->term_data ) ){
            $this->setGlobalArgs();
            return true;
        }elseif( ! empty( $this->term_data ) && is_array( $this->term_data ) ){
            $this->setTermwiseArgs();
            return true;
        }
        
        
        
        //At the end, if we get here, we will set again global setting.
        $this->setGlobalArgs();
        return true;
    }

    /**
     * this method will set global args to 
     * $this->min_value 
     * $this->max_value 
     * $this->step_value 
     *
     * @return void
     */
    protected function setGlobalArgs()
    {
        $this->where_args_on = 'global';
        // $this->min_value = $this->max_value = $this->min_value = null;
        $this->min_value = $this->options[$this->min_quantity] ?? 1;
        $this->max_value = $this->options[$this->max_quantity] ?? -1;
        $this->step_value = $this->options[$this->product_step] ?? 1;
        
        // var_dump($this->options);
    }


    protected function setTermwiseArgs()
    {
        if( empty( $this->term_data ) || ! is_array( $this->term_data ) ) return;

        $termwise_args = false;

        foreach( $this->term_data as $term_key => $values ){
            //thats keys of this term, already in database, jeta setting theke fix/thk kora ache
            $db_term_ids = array_keys($values);
            $product_term_ids = wp_get_post_terms( $this->product_id, $term_key, array( 'fields' => 'ids' ));
            $common_term_ids = array_intersect($db_term_ids, $product_term_ids);
            if( empty( $common_term_ids ) ) continue;

            $common_term_id = end($common_term_ids);
            $termwise_args = $values[$common_term_id];
            break;

        }
        if( is_array( $termwise_args ) && ! empty( $termwise_args ) ){
            $this->where_args_on = 'termwise';
            $this->min_value = $this->max_value = $this->step_value = null;

            $this->min_value = $termwise_args['_min'] ?? 1;
            $this->max_value = $termwise_args['_max'] ?? 1;
            $this->step_value = $termwise_args['_step'] ?? 1;
            return;
        }else{
            $this->setGlobalArgs();
        }

        
    }

    /**
     * Need before set input's args
     *
     * @return void
     */
    protected function finalizeArgs()
    {
        if(empty($this->max_value)){
            $this->max_value = ! empty( $this->stock_quantity ) ? $this->stock_quantity : -1;
        }
        
        if(empty($this->step_value)){
            $this->step_value = 1;
        }

        if( $this->stock_quantity && $this->max_value > $this->stock_quantity){
            $this->max_value = $this->stock_quantity;
        }

        return $this;
    }

    public function set_input_args( $args, $product )
    {
        if( $product->is_sold_individually() ) return $args;
        $this->product = $product;
        // if( ! $this->product){
        //     $this->product = $product;
        // }
        $this->product_id = $this->product->get_id();
        //Need to set organize args
        $this->assignInputArg();
        

        
        // var_dump($args);
        
        $this->finalizeArgs();
        $args['min_value'] = $this->min_value;
        $args['max_value'] = $this->max_value;
        $args['step'] = $this->step_value;

        // var_dump($args);
        var_dump($this);
        return $args;
    }


    private function getMeta($meta_key)
    {
        $value = get_post_meta($this->product_id,$meta_key,true);
        if( is_numeric( $value ) ) return (int) $value;
        return '';
    }

    public function getTermData()
    {
        return $this->term_data;
    }
    
}