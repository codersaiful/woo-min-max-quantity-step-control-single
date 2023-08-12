<?php 
namespace WC_MMQ\Admin;

use WC_MMQ\Core\Base;
use WC_MMQ\Modules\Module_Controller;

class Page_Loader extends Base
{

    public $main_slug = 'wcmmq-min-max-control';
    public $page_folder_dir;
    public $topbar_file;
    public $topbar_sub_title;

    protected $is_pro;
    protected $pro_version;
    public $license;
    public $module_controller;

    public function __construct()
    {
        $this->is_pro = defined( 'WC_MMQ_PRO_VERSION' );
        if($this->is_pro){
            $this->pro_version = WC_MMQ_PRO_VERSION;
            $this->license = \WC_MMQ_PRO::$direct;
        }
        $this->page_folder_dir = $this->base_dir . 'admin/page/';
        $this->topbar_file = $this->page_folder_dir . 'topbar.php';
        $this->topbar_sub_title = __("Manage and Settings", "wcmmq");

        $this->module_controller = new Module_Controller();
    }

    public function run()
    {
        add_action( 'admin_menu', [$this, 'admin_menu'] );
        add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );


        add_action('wp_ajax_wcmmq_save_form', [$this, 'save_form']);
        
    }

    public function save_form()
    {
        var_dump($_POST);

        die();
    }

    
    public function main_page_html()
    {
        
        $main_page_file = $this->page_folder_dir . 'main-page.php';
        if( ! is_file( $main_page_file ) ) return;
        include $main_page_file;
    }
    
    public function module_page_html()
    {
        
        $this->topbar_sub_title = __( 'Manage Module','wcmmq' );
        include $this->topbar_file;
        include $this->module_controller->dir . '/module-page.php';
    }
    
    public function browse_plugins_html()
    {
        add_filter( 'plugins_api_result', [$this, 'plugins_api_result'], 1, 3 );
        $this->topbar_sub_title = __( 'Browse our Plugins','wcmmq' );
        include $this->topbar_file;
        include $this->page_folder_dir . 'browse-plugins.php';
    }
    

    public function admin_menu()
    {
        $capability = apply_filters( 'wcmmq_menu_capability', 'manage_woocommerce' );
    
        //This bellow line will removed //If we enable bellow line, we have a include set_menu_and_fac.php file
        // add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', $capability, 'wcmmq_min_max_step', 'wcmmq_faq_page_details' );
        
        //from new class
        // add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', $capability, 'wcmmq_min_max_step', [$this, 'main_page_html'] );
        

        //THAT TO BE ENABLE AT THE END
        add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', $capability, 'wcmmq_min_max_step', [$this,'redirect_to_new_page'] );
        
        
        
        $min_max_img = $this->base_url . 'assets/images/min-max.png';
        $page_title = "Min Max and Step Control Plugin";
        $menu_title = "Min Max Control"; 
        $menu_slug = $this->main_slug;
        $callback = [$this, 'main_page_html']; 
        $icon_url = $min_max_img;
        $position = 55.11;
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url, $position);

        //Module page adding
        add_submenu_page( $this->main_slug, $this->module_controller->menu_title, $this->module_controller->menu_title, $capability, 'wcmmq_modules', [$this, 'module_page_html'] );

        add_submenu_page( $this->main_slug, esc_html__( 'Browse Plugins', 'wcmmq' ),  __( 'Browse Plugins', 'wcmmq' ), $capability, 'wcmmq-browse-plugins', [$this, 'browse_plugins_html'] );

        add_submenu_page($this->main_slug, 'Documentation', 'Documentation', 'read','https://codeastrology.com/min-max-quantity/documentation/');
        if($this->is_pro){
            add_submenu_page($this->main_slug, 'Support', 'Support', 'read','https://codeastrology.com/my-support');
        }else{
            add_submenu_page($this->main_slug, 'Support & Buy', 'Support & Buy', 'read','https://codeastrology.com/downloads/min-max-step-control-wc/');
        }
        

        //License Menu if pro version is getter or equal V2.0.8.4
        if( is_object( $this->license ) && version_compare($this->pro_version, '2.0.8.4', '>=')){
            add_submenu_page( $this->main_slug, __('Min Max Control License', 'wcmmq_pro'), __( 'License', 'wcmmq_pro' ), $capability, 'wcmmq-license', [$this->license, 'license_page'] );
        }
    }

    public function admin_enqueue_scripts()
    {
        global $current_screen;
        
        /**
         * Select2 CSS file including. 
         * 
         * @since 1.0.3
         */    
        wp_enqueue_style( 'select2-css', $this->base_url . 'assets/css/select2.min.css' );

        /**
         * Select2 jQuery Plugin file including. 
         * Here added min version. But also available regular version in same directory
         * 
         * @since 1.9
         */
        wp_enqueue_script( 'select2', $this->base_url . 'assets/js/select2.full.min.js', array( 'jquery' ), '4.0.5', true );

        
        wp_register_script( $this->plugin_prefix . '-admin-script', $this->base_url . 'assets/js/admin.js', array( 'jquery','select2' ), $this->dev_version, true );
        wp_enqueue_script( $this->plugin_prefix . '-admin-script' );

        
        $ajax_url = admin_url( 'admin-ajax.php' );
        $WCMMQ_ADMIN_DATA = array( 
            'ajax_url'       => $ajax_url,
            'site_url'       => site_url(),
            'cart_url'       => wc_get_cart_url(),
            'priceFormat'    => get_woocommerce_price_format(),
            'decimal_separator'=> '.',
            'default_decimal_separator'=> wc_get_price_decimal_separator(),
            'decimal_count'=> wc_get_price_decimals(),
            );
        wp_localize_script( $this->plugin_prefix . '-admin-script', 'WCMMQ_ADMIN_DATA', $WCMMQ_ADMIN_DATA );
        
        wp_register_style( 'ultraaddons-common-css', $this->base_url . 'assets/css/admin-common.css', false, $this->dev_version );
        wp_enqueue_style( 'ultraaddons-common-css' );

        wp_register_style( $this->plugin_prefix . 'wcmmq_css', $this->base_url . 'assets/css/admin.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . 'wcmmq_css' );

        $s_id = isset( $current_screen->id ) ? $current_screen->id : '';
        if( strpos( $s_id, $this->plugin_prefix ) !== false ){
            add_filter('admin_footer_text',[$this, 'admin_footer_text']);
            
            wp_register_style( $this->plugin_prefix . '-icon-font', $this->base_url . 'assets/fontello/css/wcmmq-icon.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-icon-font' );

            
            wp_register_style( $this->plugin_prefix . '-icon-animation', $this->base_url . 'assets/fontello/css/animation.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-icon-animation' );




            wp_register_style( $this->plugin_prefix . '-new-admin', $this->base_url . 'assets/css/new-admin.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-new-admin' );

        }

        
    }

    /**
     * Old menu page will redirect to new menu page.
     * 
     * 
     *
     * @return void
     */
    public function redirect_to_new_page()
    {
        wp_redirect(admin_url('admin.php?page=' . $this->main_slug));
    }

    public function admin_footer_text($text)
    {
        $rev_link = 'https://wordpress.org/support/plugin/woo-min-max-quantity-step-control-single/reviews/#new-post';
        $text = sprintf(
			__( 'Thank you for using Min Max Control. <a href="%s" target="_blank">%sPlease review us</a>.' ),
			$rev_link,
            '<i class="wcmmq_icon-star-filled"></i><i class="wcmmq_icon-star-filled"></i><i class="wcmmq_icon-star-filled"></i><i class="wcmmq_icon-star-filled"></i><i class="wcmmq_icon-star-filled"></i>'
		);
        return '<span id="footer-thankyou" class="wcmmq-footer-thankyou">' . $text . '</span>';
    }
    public function plugins_api_result( $res, $action, $args )
    {
        if ( $action !== 'query_plugins' ) {
            return $res;
        }
        
        if( isset( $_GET['page'] ) && $_GET['page'] == 'wcmmq-browse-plugins' ){
            //Will Continue to bottom actually
        }else{
            return $res;
        }
        $browse_plugins = get_transient( 'codersaiful_browse_plugins' );
        
        
        if( $browse_plugins ){
            return $browse_plugins;//As $res
        }
        
        
        
        $wp_version = get_bloginfo( 'version', 'display' );
        $action = 'query_plugins';
        $args = array(
            'page' => 1,
            'wp_version' => $wp_version
        );
        $args['author']          = 'codersaiful';
        $url = 'http://api.wordpress.org/plugins/info/1.2/';
        $url = add_query_arg(
                array(
                        'action'  => $action,
                        'request' => $args,
                ),
                $url
        );

        $http_url = $url;
        $ssl      = wp_http_supports( array( 'ssl' ) );
        if ( $ssl ) {
                $url = set_url_scheme( $url, 'https' );
        }

        $http_args = array(
                'timeout'    => 15,
                'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' ),
        );
        $request   = wp_remote_get( $url, $http_args );

        if ( $ssl && is_wp_error( $request ) ) {
                if ( ! wp_is_json_request() ) {
                        trigger_error(
                                sprintf(
                                        /* translators: %s: Support forums URL. */
                                        __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="%s">support forums</a>.' ),
                                        __( 'https://wordpress.org/support/forums/' )
                                ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ),
                                headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE
                        );
                }

                $request = wp_remote_get( $http_url, $http_args );
        }


        $res = json_decode( wp_remote_retrieve_body( $request ), true );
        if ( is_array( $res ) ) {
                // Object casting is required in order to match the info/1.0 format.
                $res = (object) $res;
                set_transient( 'codersaiful_browse_plugins' , $res, 32000);
        }
        
        return $res;
    }
}