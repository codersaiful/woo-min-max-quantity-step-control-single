<?php
/**
 * Plugin Name: Min Max Quantity & Step Control for WooCommerce
 * Plugin URI: https://min-max-quantity.codeastrology.com/
 * Description: Min Max Quantity & Step Control plugin offers to display specific products with minimum, maximum quantity. As well as by this plugin you will be able to set the increment or decrement step as much as you want. In a word: Minimum Quantity, Maximum Quantity and Step can be controlled.
 * Author: CodeAstrology
 * Author URI: https://codeastrology.com
 * Tags: WooCommerce, minimum quantity, maximum quantity, woocommrce quantity, customize woocommerce quantity, customize wc quantity, wc qt, max qt, min qt, maximum qt, minimum qt
 * 
 * Version: 1.0
 * Requires at least:    4.0.0
 * Tested up to:         5.8
 * WC requires at least: 3.0.0
 * WC tested up to: 	 5.6.2
 * 
 * Text Domain: wcmmq
 * Domain Path: /languages/
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Defining constant
 */

define('WC_MMQ__FILE__', __FILE__);
define('WC_MMQ_VERSION', '1.9.0');
define('WC_MMQ_PATH', plugin_dir_path(WC_MMQ__FILE__));
define('WC_MMQ_URL', plugins_url(DIRECTORY_SEPARATOR, WC_MMQ__FILE__));
//for Modules and 
define('WC_MMQ_MODULES_PATH', plugin_dir_path(WC_MMQ__FILE__) . 'modules' . DIRECTORY_SEPARATOR);


define('WC_MMQ_PLUGIN_BASE_FOLDER', plugin_basename(dirname(__FILE__)));
define('WC_MMQ_PLUGIN_BASE_FILE', plugin_basename(__FILE__));
define("WC_MMQ_BASE_URL", WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/');
define("wc_mmq_dir_base", dirname(__FILE__) . '/');
define("WC_MMQ_BASE_DIR", str_replace('\\', '/', wc_mmq_dir_base));


//$WC_MMQ = WC_MMQ::getInstance();

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


/**
 * Setting Default Quantity for Configuration page
 * It will work for all product
 * 
 * @since 1.0
 */
WC_MMQ::$default_values = array(
    '_wcmmq_min_quantity' => 1,
    '_wcmmq_default_quantity' => false,
    '_wcmmq_max_quantity' => false,
    '_wcmmq_product_step' => 1,
    '_wcmmq_prefix_quantity' => '',
    '_wcmmq_sufix_quantity' => '',
    '_wcmmq_qty_plus_minus_btn' => '1', //Added at 1.8.4 Version
    '_wcmmq_step_error_valiation'   => __( "Please enter a valid value. The two nearest valid values are [should_min] and [should_next]", 'wcmmq' ),
    '_wcmmq_msg_min_limit' => __('Minimum quantity should [min_quantity] of "[product_name]"', 'wcmmq'), //First %s = Quantity and Second %s is Product Title
    '_wcmmq_msg_max_limit' => __('Maximum quantity should [min_quantity] of "[product_name]"', 'wcmmq'), //First %s = Quantity and Second %s is Product Title
    '_wcmmq_msg_max_limit_with_already' => __('You have already [current_quantity] item of "[product_name]"', 'wcmmq'), //First %s = $current_qty_inCart Current Quantity and Second %s is Product Title
    '_wcmmq_min_qty_msg_in_loop' => __('Minimum qty is', 'wcmmq'),
    '_cat_ids' => false,
);


/**
 * Main Class for "WooCommerce Min Max Quantity & Step Control"
 * We have included file from __constructor of this class [WC_MMQ]
 */
class WC_MMQ {

    /**
     * It's need for Varification purchase code of CodeCanyon
     *
     * @var type int
     */
    public static $item_id = 22962198;
    public static $options_name = 'wcmmq_codecanyon_purchase_code';

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = WC_MMQ_VERSION;

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_WC_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '5.6';

    /**
     * Default keyword for WCMMQ
     * You will find this in wp_options table of database
     */
    const KEY = 'wcmmq_universal_minmaxstep';

    /*
     * Set default value based on default keyword.
     * All value will store in wp_options table based on Keyword wcmmq_universal_minmaxstep
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
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.7.0
     *
     * @access public
     * @static
     *
     * @return WC_MMQ An instance of the class.
     */
    public static function instance() {
        if (!( self::$_instance instanceof self )) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**

      public static function getInstance() {
      if ( ! ( self::$_instance instanceof self ) ) {
      self::$_instance = new self();
      }

      return self::$_instance;
      }
     */
    public function __construct() {

        //Condition and check php verion and WooCommerce activation
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        add_action('init', [$this, 'i18n']);

        $dir = dirname(__FILE__);

        if ( is_admin() ) {
     

            include_once $dir . '/admin/functions.php';
            include_once $dir . '/admin/product_panel.php';
            include_once $dir . '/admin/add_options_admin.php';
            include_once $dir . '/admin/set_menu_and_fac.php';
            include_once $dir . '/admin/plugin_setting_link.php';
        }
        
        
        include_once $dir . '/includes/enqueue.php';
        include_once $dir . '/includes/set_max_min_quantity.php';
    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function i18n() {
        load_plugin_textdomain('wcmmq');
    }


    /**
     * Installation function for Plugn WC_MMQ
     * 
     * @since 1.0
     */
    public static function install() {
        //check current value
        $current_value = get_option(self::KEY);
        $default_value = self::$default_values;
        $changed_value = false;
        //Set default value in Options
        if ($current_value) {
            foreach ($default_value as $key => $value) {
                if (isset($current_value[$key]) && $key != 'plugin_version') { //We will add Plugin version in future
                    $changed_value[$key] = $current_value[$key];
                } else {
                    $changed_value[$key] = $value;
                }
            }
            update_option(self::KEY, $changed_value);
        } else {
            update_option(self::KEY, $default_value);
        }
    }

    /**
     * Getting default key and value 's array
     * 
     * @return Array getting default value for basic plugin
     * @since 1.0
     */
    public static function getDefaults() {
        return self::$default_values;
    }

    /**
     * Getting Array of Options of wcmmq_universal_minmaxstep
     * 
     * @return Array Full Array of Options of wcmmq_universal_minmaxstep
     * 
     * @since 1.0.0
     */
    public static function getOptions() {
        return get_option(self::KEY);
    }

    /**
     * Getting Array of Options of wcmmq_universal_minmaxstep
     * 
     * @return Array Full Array of Options of wcmmq_universal_minmaxstep
     * 
     * @since 1.0.0
     */
    public static function getOption($kewword = false) {
        $data = get_option(self::KEY);
        return $kewword && isset($data[$kewword]) ? $data[$kewword] : false;
    }

    public static function minMaxStep($kewword = false, $product_id = false) {
        $data = get_option(self::KEY);
        $cat_ids = $data['_cat_ids'];
        //var_dump($cat_ids);
        $check_arr = false;
        if (isset($cat_ids) && is_array($cat_ids) && $product_id && !empty($product_id)) {
            $product_cat_ids = wc_get_product_cat_ids($product_id);
            $check_arr = is_array($product_cat_ids) ? array_intersect($cat_ids, $product_cat_ids) : false;
        }

        if (is_array($check_arr) && count($check_arr) > 0) {
            return $kewword && isset($data[$kewword]) ? $data[$kewword] : false;
        }

        if (!$check_arr && isset($cat_ids) && is_array($cat_ids) && $product_id && !empty($product_id)) {
            $default = WC_MMQ::getDefaults();
            return $kewword && isset($default[$kewword]) ? $default[$kewword] : false;
        }
        /*
          $cat_ids_diff = is_array( $cat_ids ) ? array_diff( $product_cat_ids, $cat_ids ) : false;
          if(!$cat_ids){
          return $kewword && isset( $data[$kewword] ) ? $data[$kewword] : false;
          }
          if($cat_ids && $cat_ids_diff && is_array( $cat_ids ) && count( $cat_ids_diff ) > count( $cat_ids ) ){
          return $kewword && isset( $data[$kewword] ) ? $data[$kewword] : false;
          }
         */
        //$default = WC_MMQ::getDefaults();
        return self::getOption($kewword);
        //return $kewword && isset( $default[$kewword] ) ? $default[$kewword] : false;
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
    public static function getPluginData() {
        if (is_admin())
            return get_plugin_data(__FILE__);
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
    public static function vd($something) {
        echo '<div style="width:400px; margin: 30px 0 0 181px;">';
        var_dump($something);
        echo '</div>';
    }

    public function admin_notice_missing_main_plugin(){
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

           $message = sprintf(
                   esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wcmmq' ),
                   '<strong>' . esc_html__( 'Min Max Quantity & Step Control for WooCommerce', 'wcmmq' ) . '</strong>',
                   '<strong><a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank">' . esc_html__( 'WooCommerce', 'wcmmq' ) . '</a></strong>'
           );

           printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );
    }
    

}


//Call to Instance
$WC_MMQ = WC_MMQ::instance();

register_activation_hook(__FILE__, array('WC_MMQ', 'install'));
register_deactivation_hook(__FILE__, array('WC_MMQ', 'uninstall'));
