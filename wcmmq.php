<?php
/**
 * Plugin Name: WC Min Max Quantity
 * Plugin URI: https://codersaiful.net/woo-product-table-pro/
 * Description: WooCommerce Set Minimum and Maximum Quantity for Simple and Variations Type Products.
 * Author: Saiful Islam
 * Author URI: https://codecanyon.net/user/codersaiful
 * Tags: WooCommerce, minimum quantity, maximum quantity, woocommrce quantity, customize woocommerce quantity, customize wc quantity, wc qt, max qt, min qt, maximum qt, minimum qt
 * 
 * Version: 1.0
 * Requires at least:    4.0.0
 * Tested up to:         4.9.8
 * WC requires at least: 3.0.0
 * WC tested up to: 	 3.4.4
 * 
 * Text Domain: wcmmq
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Defining constant
 */
define( 'WC_MMQ_PLUGIN_BASE_FOLDER', plugin_basename( dirname( __FILE__ ) ) );
define( 'WC_MMQ_PLUGIN_BASE_FILE', plugin_basename( __FILE__ ) );
define( "WC_MMQ_BASE_URL", WP_PLUGIN_URL . '/'. plugin_basename( dirname( __FILE__ ) ) . '/' );
define( "wc_mmq_dir_base", dirname( __FILE__ ) . '/' );
define( "WC_MMQ_BASE_DIR", str_replace( '\\', '/', wc_mmq_dir_base ) );



$WC_MMQ = WC_MMQ::getInstance();


/**
 * Main Class for WC Min Max Quantity Plugin
 */
class WC_MMQ {
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
            
            require_once $dir . '/admin/add_options_admin.php';
            require_once $dir . '/admin/set_menu_and_fac.php';
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
     * Installation function for Plugn WC_MMQ
     * 
     * @since 1.0
     */
    public static function install() {
        //Nothing to do for now
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
register_activation_hook(__FILE__, array( 'WC_MMQ','install' ) );
register_deactivation_hook( __FILE__, array( 'WC_MMQ','uninstall' ) );