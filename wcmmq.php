<?php
/**
 * Plugin Name: WooCommerce Min Max Quantity & Step Control Single
 * Plugin URI: https://codecanyon.net/item/woocommerce-min-max-quantity-step-control/22962198
 * Description: WooCommerce Min Max Quantity & Step Control  plugin offers to display specific products with minimum, maximum quantity. As well as by this plugin you will be able to set the increment or decrement step as much as you want. In a word: Minimum Quantity, Maximum Quantity and Step can be controlled.
 * Author: Saiful Islam
 * Author URI: https://codecanyon.net/user/codersaiful
 * Tags: WooCommerce, minimum quantity, maximum quantity, woocommrce quantity, customize woocommerce quantity, customize wc quantity, wc qt, max qt, min qt, maximum qt, minimum qt
 * 
 * Version: 1.6
 * Requires at least:    4.0.0
 * Tested up to:         5.2.2
 * WC requires at least: 3.0.0
 * WC tested up to: 	 3.7.0
 * 
 * Text Domain: wcmmq
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Defining constant
 */
define( 'WC_MMQ_S_PLUGIN_BASE_FOLDER', plugin_basename( dirname( __FILE__ ) ) );
define( 'WC_MMQ_S_PLUGIN_BASE_FILE', plugin_basename( __FILE__ ) );
define( "WC_MMQ_S_BASE_URL", WP_PLUGIN_URL . '/'. plugin_basename( dirname( __FILE__ ) ) . '/' );
define( "wc_mmq_dir_base", dirname( __FILE__ ) . '/' );
define( "WC_MMQ_S_BASE_DIR", str_replace( '\\', '/', wc_mmq_dir_base ) );



$WC_MMQ_S = WC_MMQ_S::getInstance();

/**
 * Setting Default Quantity for Configuration page
 * It will work for all product
 * 
 * @since 1.0
 */
WC_MMQ_S::$default_values = array(
    '_wcmmq_s_min_quantity'   => 1,
    '_wcmmq_s_max_quantity'   =>  false,
    '_wcmmq_s_product_step'   => 1,
    '_wcmmq_s_msg_min_limit' => __( 'Minimum quantity should %s of "%s"', 'wcmmq' ), //First %s = Quantity and Second %s is Product Title
    '_wcmmq_s_msg_max_limit' => __( 'Maximum quantity should %s of "%s"', 'wcmmq' ), //First %s = Quantity and Second %s is Product Title
    '_wcmmq_s_msg_max_limit_with_already' => __( 'You have already %s item of "%s"', 'wcmmq' ), //First %s = $current_qty_inCart Current Quantity and Second %s is Product Title
    '_wcmmq_s_min_qty_msg_in_loop' => __( 'Minimum qty is', 'wcmmq' ),
);

/**
 * Main Class for "WooCommerce Min Max Quantity & Step Control"
 * We have included file from __constructor of this class [WC_MMQ_S]
 */
class WC_MMQ_S {
    
    /**
     * Default keyword for WCMMQ
     * You will find this in wp_options table of database
     */
    const KEY = 'wcmmq_s_universal_minmaxstep';
    
    /*
     * Set default value based on default keyword.
     * All value will store in wp_options table based on Keyword wcmmq_s_universal_minmaxstep
     * 
     * @Sinc Version 1.0.0
     */
    public static $default_values = array();
    
    /**
     * For Instance
     *
     * @var Object 
     * @since 1.0
     */
    private static $_instance;
    
    /**

       public static function getInstance() {
               if ( ! ( self::$_instance instanceof self ) ) {
                       self::$_instance = new self();
               }

               return self::$_instance;
       }
     */

    public function __construct() {
        $dir = dirname( __FILE__ );
        
        //Test File will load always when developing
        //require_once $dir . '/admin/test.php'; //Only for Test Perpose.
        
        if( is_admin() ){
            
            require_once $dir . '/admin/product_panel.php';
            require_once $dir . '/admin/add_options_admin.php';
            require_once $dir . '/admin/set_menu_and_fac.php';
            require_once $dir . '/admin/plugin_setting_link.php';
        }
        require_once $dir . '/includes/set_max_min_quantity.php';
    }
    
    public static function getInstance() {
        if( ! ( self::$_instance instanceof self ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Installation function for Plugn WC_MMQ_S
     * 
     * @since 1.0
     */
    public static function install() {
        //check current value
        $current_value = get_option( self::KEY );
        $default_value = self::$default_values;
        $changed_value = false;
        //Set default value in Options
        if($current_value){
           foreach( $default_value as $key=>$value ){
              if( isset($current_value[$key]) && $key != 'plugin_version' ){ //We will add Plugin version in future
                 $changed_value[$key] = $current_value[$key];
              }else{
                  $changed_value[$key] = $value;
              }
           }
           update_option(  self::KEY , $changed_value );
        }else{
           update_option(  self::KEY , $default_value );
        }
    }
    
    /**
     * Getting default key and value 's array
     * 
     * @return Array getting default value for basic plugin
     * @since 1.0
     */
    public static function getDefaults(){
        return self::$default_values;
    }

    /**
     * Getting Array of Options of wcmmq_s_universal_minmaxstep
     * 
     * @return Array Full Array of Options of wcmmq_s_universal_minmaxstep
     * 
     * @since 1.0.0
     */
    public static function getOptions(){
        return get_option( self::KEY );
    }
    
    /**
     * Getting Array of Options of wcmmq_s_universal_minmaxstep
     * 
     * @return Array Full Array of Options of wcmmq_s_universal_minmaxstep
     * 
     * @since 1.0.0
     */
    public static function getOption( $kewword = false ){
        $data = get_option( self::KEY );
        return $kewword ? $data[$kewword] : false;
    }
    
    /**
     * Un instalation Function
     * 
     * @since 1.0
     */
    public static function uninstall() {
        //Nothing to do for now
    }
    
    /**
    * Getting full Plugin data. We have used __FILE__ for the main plugin file.
    * 
    * @since V 1.0
    * @return Array Returnning Array of full Plugin's data for This Woo Product Table plugin
    */
    public static function getPluginData(){
       return get_plugin_data( __FILE__ );
    }

    /**
    * Getting Version by this Function/Method
    * 
    * @return type static String
    */
    public static function getVersion() {
       $data = self::getPluginData();
       return $data['Version'];
    }

    /**
    * Getting Version by this Function/Method
    * 
    * @return type static String
    */
    public static function getName() {
       $data = self::getPluginData();
       return $data['Name'];
    }
    
    /**
     * For checking anything
     * Only for test, Nothing for anything else
     * 
     * @since 1.0
     * @param void $something
     */
    public static function vd( $something ){
        echo '<div style="width:400px; margin: 30px 0 0 181px;">';
        var_dump( $something );
        echo '</div>';
    }
}

register_activation_hook(__FILE__, array( 'WC_MMQ_S','install' ) );
register_deactivation_hook( __FILE__, array( 'WC_MMQ_S','uninstall' ) );