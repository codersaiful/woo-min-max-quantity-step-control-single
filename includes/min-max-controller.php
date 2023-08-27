<?php
namespace WC_MMQ\Includes;

use WC_MMQ;
use WC_MMQ\Core\Base;

/**
 * Main Min_Max_Controller class
 * 
 * @todo ekhono variation onujayi ze kaj hobe seta kora hoyni.
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Min_Max_Controller extends Base
{

    public $product_id;
    public $product_name;
    public $get_product_type;
    /**
     * It's need, only when cart page, otherwise it will null
     *
     * @var int
     */
    public $variation_id;
    public $variation_name;
    public $get_variation_type;

    //Important value
    public $min_value;
    public $max_value;
    public $step_value;
    public $stock_quantity;
    public $backorders;
    public $backorders_status = false;
    public $qty_inCart;

    //Important key
    public $key_prefix = WC_MMQ_PREFIX;
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

    public $input_args = [];
    public $variations_args = [];

    /**
     * Filter hook provided args,
     * which I stored at $temp_args
     * Specially of input box,
     * which is provided by WooCommerce using 
     * filter hook like:
     * *woocommerce_loop_add_to_cart_args
     * *woocommerce_quantity_input_args
     * *woocommerce_loop_add_to_cart_args
     *
     * @var array
     */
    public $temp_args = [];
    public $term_data;
    protected $options;
    protected $product;
    protected $variation_product;


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

        /**
         * You can ask, why need again value asign
         * indivisually where we already added at 
         * * woocommerce_quantity_input_args
         * * woocommerce_loop_add_to_cart_args
         * * woocommerce_available_variation
         * 
         * ACTUALLY SOME THIRD PARTY PLUGIN DIDN'T USE these common filter 
         * for that side cart plugin, so we have to use also individule also
         * 
         */
        add_filter( 'woocommerce_quantity_input_step', [$this, 'quantity_input_step'], 9999, 2 );
        add_filter( 'woocommerce_quantity_input_min', [$this, 'quantity_input_min'], 9999, 2 );
        add_filter( 'woocommerce_quantity_input_max', [$this, 'quantity_input_max'], 9999, 2 );

        //validation setup
        add_filter('woocommerce_add_to_cart_validation', [$this, 'add_to_cart_validation'], 10, 5);
        add_filter('woocommerce_update_cart_validation', [$this, 'update_cart_validation'], 10, 4);

        $this->controlVariationsMinMax();
    }
    
    /**
     * Congrolling Min Max Step for All Variation
     * without Premium constant 'WC_MMQ_PRO_VERSION'
     * This method will not work.
     * Obviously need 'WC_MMQ_PRO_VERSION' Constant
     * 
     * we will call action hook for woocommerce_single_variation
     * and for wpt_action_variation
     * 
     * Compatible with Woo Product Table also
     * 
     * @since 4.9.0
     *
     * @return void
     */
    public function controlVariationsMinMax()
    {
        if( ! $this->is_pro) return;
        add_action('woocommerce_single_variation',[$this, 'single_variation_handle']);
        add_action('wpt_action_variation',[$this, 'single_variation_handle']);

        /**
         * Remove action should here
         * actually first time, I input it at $this->single_variation_handle()
         * but that's not work.
         * So I added it at this method
         * @since 4.9.1
         */
        remove_action('woocommerce_single_variation','wcmmq_pro_js_for_variation_product');
        remove_action('wpt_action_variation','wcmmq_pro_js_for_variation_product');
    }

    /**
     * Temporarily set Min Max and step 
     * based on Custom Field
     * 
     * Actually requirement data only available on Pro version
     * because: variation min max step setting only available on premium version
     * That's why, we set a condition by 'WC_MMQ_PRO_VERSION' constant
     * 
     * @since 4.9.0
     * @author Saiful Islam <codersaiful@gmail.com>
     *
     * @return void
     */
    public function single_variation_handle()
    {
        if( ! defined('WC_MMQ_PRO_VERSION') ) return;
        global $product;
        $this->product_id = $product->get_id();
        $this->product = wc_get_product( $this->product_id );
        $variables = $product->get_children();
        if(empty($variables) && ! is_array( $variables )) return;
        foreach( $variables as $variable_id){
            
            $this->variation_id = $variable_id;
            $this->variation_product = wc_get_product( $this->variation_id );
            $this->get_variation_type = $this->variation_product->get_type();   
            $this->organizeAndFinalizeArgs();
        }

        if( empty($this->variations_args) ) return;

        $data = apply_filters( 'wcmmq_variation_data_for_json', $this->variations_args, $product );
        $data = wp_json_encode( $data );
        ?>
<script  type='text/javascript'>
(function($) {
    'use strict';
    $(document).ready(function($) {
        var product_id = "<?php echo $product->get_id(); ?>";
        var variation_data = '<?php echo $data; ?>';
        variation_data = JSON.parse(variation_data);
        var form_selector = 'form.variations_form.cart[data-product_id="' + product_id + '"]';
        $(document).on( 'found_variation', form_selector, function( event, variation ) {
            // console.log(variation);
        });
  
        $(document.body).on('change',form_selector + ' input.variation_id',function(){
           
            //$( form_selector + ' input.input-text.qty.text' ).triggerHandler( 'binodon');
            var variation_id = $(form_selector + ' input.variation_id').val();
            var qty_box = $(form_selector + ' input.input-text.qty.text');
            var qty_boxWPT = $('.product_id_' + product_id + ' input.input-text.qty.text');

            if(typeof variation_id !== 'undefined' && variation_id !== ''  && variation_id !== ' '){
                var min,max,step,basic;

                min = variation_data[variation_id]['min_quantity'];
                if(typeof min === 'undefined'){
                    return false;
                }

                max = variation_data[variation_id]['max_quantity'];
                step = variation_data[variation_id]['step_quantity'];

                // basic = variation_data[variation_id]['default_quantity'];               
                // if(basic === '' || basic === false){
                //     basic = min;
                // }
                basic = min;
                var lateSome = setInterval(function(){
                    qty_box.attr({
                        min:min,
                        max:max,
                        step:step,
                        value:min
                    });
                    qty_boxWPT.attr({
                        min:min,
                        max:max,
                        step:step,
                        value:min
                    });
                    qty_box.val(basic).trigger('change');
                    clearInterval(lateSome);
                },500);

            }
            
            
        });

    });
})(jQuery);
</script>        
        <?php 

    }
    
    
    /**
     * both method's use at one method
     * Used methods: $this->assignInputArg() AND $this->finalizeArgs() 
     * Obviously need to set $this->product and $this->product_id
     * 
     * **************************
     * USES:
     * **************************
     * $this->assignInputArg();
     * $this->finalizeArgs();
     * 
     * **************************
     * IMPORTANCE NOTICE:
     * **************************
     * Everywhere, where we will assign $this->assignInputArg() after that
     * I need $this->finalizeArgs() - finalizeArgs maintain original and final args value.
     * such: $this->min_value, $this->max_value, $this->step_value, $this->input_args etc. which is very important 
     * 
     *
     * @return void
     */
    protected function organizeAndFinalizeArgs(){
        if( is_single() && 'variable' === $this->product->get_type() ){
            $this->variation_id = $this->temp_args['variation_id'] ?? 0;
            $this->variation_product = wc_get_product( $this->variation_id );
        }elseif('variation' === $this->product->get_type() ){
            //As it's variation product, So I have to assign variation id and product at the begining this statement
            $this->variation_id = $this->product->get_id();
            $this->variation_product = wc_get_product( $this->variation_id );
            $this->get_variation_type = $this->variation_product->get_type();

            $this->product_id = $this->product->get_parent_id();
            $this->product = wc_get_product( $this->product_id );
        }elseif( ! $this->is_pro ){
            /**
             * Actually not available pro version
             * Then we have do generate variation_ID null
             * Otherwise it will not work
             * 
             * Checked for Variation
             * 
             * @since 4.5.11
             */
            $this->variation_id = null;
            $this->variation_product = null;
        }
        $check_name = 'check_' . $this->product_id . '_' . $this->variation_id;

        if( $this->$check_name ) return;
        $this->assignInputArg();
        $this->finalizeArgs();

        $this->$check_name = true;
    }

    public function checkQtyInCart()
    {
        global $woocommerce;
        if( ! is_object($woocommerce->cart)) return 0;
        if( ! method_exists($woocommerce->cart, 'get_cart')) return 0;
        $return = 0;

        foreach($woocommerce->cart->get_cart() as $key => $value ) {

            $temp_quantity = $value['quantity'] ?? 0;
            if( $this->variation_id && $this->is_pro && $this->product_id == $value['product_id'] && $this->variation_id == $value['variation_id'] ) {
                $return = $temp_quantity;
                break;
            }elseif($this->product_id == $value['product_id'] && empty( $value['variation_id'] ) ){
                $return = $temp_quantity;
                break;
            }elseif(! $this->is_pro && $this->product_id == $value['product_id'] && ! empty( $value['variation_id'] ) ){
                $return += $temp_quantity;
            }
        }
        return $return;
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

        if( ! $this->product) return;

        //Some important data assing on property
        $this->product_name = get_the_title( $this->product_id );
        $this->variation_name = $this->variation_id ? get_the_title( $this->variation_id ) : null;
        $this->qty_inCart = $this->checkQtyInCart();//wcmmq_check_quantity_in_cart( $this->product_id, $this->variation_id );

        $this->is_args_organized = true;
        $this->stock_quantity = $this->product->get_stock_quantity();
        $this->backorders = $this->product->get_backorders();

        if( $this->variation_id ){
            $this->variation_product = wc_get_product( $this->variation_id );
            $this->get_variation_type = $this->variation_product->get_type();
            $this->stock_quantity = $this->variation_product->get_stock_quantity();
            $this->backorders = $this->variation_product->get_backorders();
        }
        $this->backorders_status = $this->backorders !== 'no' ? true : false;
        // var_dump($this->variation_product);
        //First check from single product and if it on single page
        $this->min_value = $this->getMeta( $this->min_quantity );
        $this->max_value = $this->getMeta( $this->max_quantity );
        $this->step_value = $this->getMeta( $this->product_step );

        do_action( 'wcmmq_arg_asign', $this );

        if( $this->is_pro && $this->setIfVariationArgs() ) return true;

        //Return here if found in single
        if( ! empty( $this->min_value ) || ! empty( $this->max_value ) || ! empty( $this->step_value )  || $this->min_value === 0 || $this->min_value === '0' ){
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
     * If only found min max input box in variation
     * if found any one, we will set min_value,max_value,step_value
     * otherwise, we will not set anything.
     *
     * @return void
     */
    public function setIfVariationArgs()
    {
        if( ! $this->variation_id ) return;
        if( ! $this->is_pro ) return;
        $min_v = $this->getMetaVariation( $this->min_quantity );
        $max_v = $this->getMetaVariation( $this->max_quantity );
        $step_v = $this->getMetaVariation( $this->product_step );
        if( ! empty( $min_v ) || ! empty( $max_v ) || ! empty( $step_v )){
            $this->min_value = $min_v;
            $this->max_value = $max_v;
            $this->step_value = $step_v;
            return true;
        }
        
        return;
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
    public function finalizeArgs()
    {

        if( empty($this->min_value) && $this->min_value !== '0' ){
            $this->min_value = '1';
        }

        if( empty( $this->max_value ) && ! $this->backorders_status ){
            $this->max_value = ! empty( $this->stock_quantity ) ? $this->stock_quantity : '';
        }
        
        if(empty($this->step_value)){
            $this->step_value = '1';
        }

        if( ! $this->backorders_status && $this->stock_quantity && $this->max_value > $this->stock_quantity ){
            $this->max_value = $this->stock_quantity;
        }

        if( ! is_array( $this->input_args ) ){
            $this->input_args = [];
        }


        $this->input_args = array(
            'min_quantity' => $this->min_value,
            'max_quantity' => $this->max_value,
            'step_quantity' => $this->step_value,
            'current_quantity' => $this->qty_inCart,
            'product_id'=> $this->product_id,
            'product_name'=> $this->product_name,
            'variation_id'=> $this->variation_id,
            'variation_name'=> $this->variation_name,
        );
        if(!empty($this->variation_id)){
            $this->variations_args[$this->variation_id] = array(
                'min_quantity' => $this->min_value,
                'max_quantity' => $this->max_value,
                'step_quantity' => $this->step_value,
                // 'variation_name'=> $this->variation_name,
            );
        }
        
        return $this;
    }

    /**
     * input argsment setup for each place, specially
     * where used common args filter.
     *
     * @param array $args
     * @param object $product
     * @return array
     */
    public function set_input_args( $args, $product )
    {
        $this->temp_args = $args;

        if( $product->is_sold_individually() ) return $args;
        $this->product = $product;
        $this->product_id = $this->product->get_id();
        $this->get_product_type = $this->product->get_type();

        

        //Need to set organize args and need to finalize
        $this->organizeAndFinalizeArgs();

        //for more or for vairable product 
        //check woocommerce/templates/single-product/add-to-cart/variable.php file
        if( $this->product->get_type() === 'variable' && ! $this->is_pro){
            $args['min_qty'] = $this->min_value;
            $args['max_qty'] = $this->max_value;
        }

        $args['min_value'] = $this->min_value;
        $args['max_value'] = $this->max_value;
        $args['step'] = $this->step_value;
        $args['classes'][] = 'wcmmq-qty-input-box';

        if( is_single() && ! empty( $args['input_name'] ) && $args['input_name'] === 'quantity'  ){
            $args['input_value'] = $this->min_value;
        }

        if( ! empty( $args['quantity'] ) ){
            $args['input_value'] = $args['quantity'] ?? $this->min_value;
            $args['quantity'] = $this->min_value;
        }

        if(isset($args['attributes']['data-product_id']) || isset($args['attributes']['data-product_sku'])){
            $args['attributes']['title'] = $this->options[$this->key_prefix . 'min_qty_msg_in_loop'] . ' ' . $this->min_value;
        }
        return apply_filters('wcmmq_single_product_min_max_condition', $args, $product);
    }

    /**
     * Individule quantity setup using single filter
     *
     * @param int|string $qty
     * @param object $product
     * @return int|string
     */
    public function quantity_input_step($qty, $product)
    {
        if( ! method_exists($product, 'is_sold_individually') ) return $qty;
        if( $product->is_sold_individually() ) return $qty;
        $this->product = $product;
        $this->product_id = $this->product->get_id();

        //Need to set organize args and need to finalize
        $this->organizeAndFinalizeArgs();

        return $this->step_value;
    }

    /**
     * Individule quantity setup using single filter
     *
     * @param int|string $qty
     * @param object $product
     * @return int|string
     */
    public function quantity_input_min($qty, $product)
    {
        if( ! method_exists($product, 'is_sold_individually') ) return $qty;
        if( $product->is_sold_individually() ) return $qty;
        $this->product = $product;
        $this->product_id = $this->product->get_id();

        //Need to set organize args and need to finalize
        $this->organizeAndFinalizeArgs();

        return $this->min_value;
    }

    /**
     * Individule quantity setup using single filter
     *
     * @param int|string $qty
     * @param object $product
     * @return int|string
     */
    public function quantity_input_max($qty, $product)
    {
        if( $product->is_sold_individually() ) return $qty;
        $this->product = $product;
        $this->product_id = $this->product->get_id();

        //Need to set organize args and need to finalize
        $this->organizeAndFinalizeArgs();

        return $this->max_value;
    }

    /**
     * $this->product compolsury need to set before this method
     * Specially for update_cart_validation() method and add_to_cart_validation() method of this class
     * 
     * ************************
     * IMPORTANT
     * ************************
     * dON'T CALL OTHER PLACE
     * only call after set $this->product property
     * **** sob jaygay use kora jabena. sudhu cart validation and add to cart validation er somoy
     * eta use kora jabe
     * 
     * *******************
     * WHAT USED
     * ********************
     * $this->product_id = $product_id;
        $this->variation_id = $variation_id;
        //Need this following line to dispay ['inputed_quantity'] in message, if used ['inputed_quantity'] on message box.
        $this->input_args['inputed_quantity'] = $quantity;

        $this->organizeAndFinalizeArgs();
     *
     * @param int|string $product_id
     * @param int|string $variation_id
     * @param int|string $quantity
     * @return void
     */
    public function OrganizeValidPropertyAndOrganize( $product_id, $variation_id, $quantity)
    {
        $this->product_id = $product_id;
        $this->variation_id = $variation_id;
        //Need this following line to dispay ['inputed_quantity'] in message, if used ['inputed_quantity'] on message box.
        $this->input_args['inputed_quantity'] = $quantity;

        $this->organizeAndFinalizeArgs();
    }
    public function update_cart_validation($bool, $cart_item_key, $values, $quantity)
    {

        /**
         * If anytime, we want to remove the validation change, we have to
         * set falst the filter hook value for 'wcmmq_cart_validation_check'
         * 
         * @since 4.3.0
         * @author Saiful Islam <codersaiful@gmail.com>
         */
        $validation_check = apply_filters( 'wcmmq_cart_validation_check', true, $values);
        if( ! $validation_check ) return $bool;
        $product_id = $values['product_id'] ?? 0;
        $variation_id = $values['variation_id'] ?? null;

        $this->product = wc_get_product( $product_id );
        if( method_exists( $this->product, 'is_sold_individually' ) && $this->product->is_sold_individually() ) return $bool;

        $this->OrganizeValidPropertyAndOrganize( $product_id, $variation_id, $quantity);

        //First Check modulous
        $modulous = $this->getModulous($quantity);
        if( ! $modulous ) return false;
        
        if($this->max_value > 0 && $quantity <= $this->max_value && $quantity >= $this->min_value) return true;
        if($this->max_value < 0 && $this->min_value <= $quantity) return true;

        if($this->max_value > 0 && $quantity > $this->max_value){
            $this->displayErrorMessage( 'msg_max_limit' );
            return false;
        }elseif($this->min_value && $quantity < $this->min_value){
            
            $this->displayErrorMessage( 'msg_min_limit' );
            return false;
        }


        return $bool;
    }
    
    public function add_to_cart_validation( $bool,$product_id, $quantity, $variation_id = 0, $variations = false)
    {

        /**
         * If anytime, we want to remove the validation change, we have to
         * set falst the filter hook value for 'wcmmq_add_validation_check'
         * 
         * @since 4.3.0
         * @author Saiful Islam <codersaiful@gmail.com>
         * 
         * @todo Maybe get_the_ID() is not need here, we have to fix it
         */
        $validation_check = apply_filters('wcmmq_add_validation_check',true, $product_id, get_the_ID());
        if(!$validation_check) return $bool;

        $this->product = wc_get_product( $product_id );
        if( method_exists( $this->product, 'is_sold_individually' ) && $this->product->is_sold_individually() ) return $bool;

        $this->OrganizeValidPropertyAndOrganize( $product_id, $variation_id, $quantity);       

        //First Check modulous
        $modulous = $this->getModulous( $quantity );
        if( ! $modulous ) return false;

        $total_quantity = $this->qty_inCart + $quantity;

        
        if( $total_quantity <= $this->max_value && $total_quantity >= $this->min_value && $modulous ){
            return $bool;
        }elseif($this->min_value && $total_quantity < $this->min_value ){
            $this->displayErrorMessage( 'msg_min_limit' );
            return false;
        }elseif( $this->max_value > 0 && $total_quantity > $this->max_value ){
            if( $this->qty_inCart > 0 ){
                $this->displayErrorMessage( 'msg_max_limit_with_already' );
            }
            
            $this->displayErrorMessage( 'msg_max_limit' );
            return false;
        }

        
        return $bool;
    }

    /**
     * updated modulous 
     * Only we will check when our qty will getter then min and smaller then max
     * 
     *  If max value is empty then old php version return false of this statement ( $this->max_value < 0 )
     *  So I (Fazle Bari) have add this statement ( empty($this->max_value ) also.
     * @param int|string $quantity
     * @return bool default value is true, if in condition, then it will check using filter hook
     */
    protected function getModulous( $quantity )
    {
        if( $this->min_value <= $quantity && ( empty($this->max_value ) || $this->max_value < 0 || ( $this->max_value > 0 && $this->max_value >= $quantity ) ) ){
            return apply_filters( 'wcmmq_modulous_validation', false, $this->product_id, $this->variation_id, $quantity, $this->min_value, $this->step_value );
        }
        return true;
    }

    /**
     * To display Error message,
     * We will use this method.
     * 
     * specially for validation method when add to cart
     * It shows Error type notice.
     * 
     * @uses wcmmq_get_message() we will remove it asap
     * @uses wcmmq_message_convert_replace() we will remove it asap
     * @uses wc_add_notice()
     *
     * @param string $message_key
     * @return void
     */
    public function displayErrorMessage( $message_key )
    {
        $message = $this->getRawMsg( $message_key );
        $message = $this->messageReplace($message);
        wc_add_notice( $message, 'error' );
    }

    /**
     * retrive message from $this->options 
     * here we will use array_key as parametter.
     * such: msg_min_limit or msg_max_limit
     * This way, there are so many message available.
     * * msg_min_limit
     * * msg_max_limit
     * * msg_max_limit_with_already
     * 
     * IMPORTANT NOTICE:
     * actually in old version, we used '_wcmmq_s_' at the beggining of all key
     * but in new version we have fixed it and remove it.
     * for security, i used here: $this->key_prefix = WC_MMQ_PREFIX
     * 
     * So no need to use prefix when call getRawMsg() method
     *
     * @param String $keyword such: msg_min_limit or msg_max_limit
     * @return String
     */
    public function getRawMsg( $keyword )
    {
        $keyword = $this->key_prefix . $keyword;
        return $this->options[$keyword] ?? '';
    }

    /**
     * Message will replace with provided shortcode.
     * we will use $this->input_args for replace value.
     * if use [max_quantity] in your message, this method will replace with $this->input_args['max_quantity']
     *
     * @param string $message
     * @return string
     */
    public function messageReplace( $message )
    {
        $message = __( $message, 'wcmmq' );
        $defaults = array(
            'min_quantity' => false,
            'max_quantity' => false,
            'product_name'=> false,
            'current_quantity'=> false,
        );
        $args = wp_parse_args( $this->input_args, $defaults );
        $args = apply_filters( 'wcmmq_message_replaced_shortcode_args', $args );
        $arr_keys = array_keys($args);
        $find_arr = array_map(function($val){
            return "[$val]";
        },$arr_keys);

        $reslt = str_replace($find_arr, $args, $message);
        $reslt = apply_filters('wcmmq_validation_message', $reslt, $this->product_id );
        return $reslt;
    }

    /**
     * get_post_meta($this->product_id,$meta_key,true) 
     * Get post meta using wp function get_post_meta()
     * post_id already generated as $this->product_id.
     * actually this method will only work properly after call $this->assignInputArg()
     * where we set $this->product_id otherwise, it will return empty -> ''
     *
     * @param int $meta_key
     * @return mixed
     */
    private function getMeta($meta_key)
    {
        $value = get_post_meta($this->product_id,$meta_key,true);
        if( is_numeric( $value ) ) return $value;
        return '';
    }

    private function getMetaVariation($meta_key)
    {
        $value = get_post_meta($this->variation_id,$meta_key,true);
        if( is_numeric( $value ) ) return $value;
        return '';
    }

    /**
     * Actually term_data set at __construct() method
     * $this->term_data assign already at constructor method
     *
     * @return array|null
     */
    public function getTermData()
    {
        return $this->term_data;
    }
    
}