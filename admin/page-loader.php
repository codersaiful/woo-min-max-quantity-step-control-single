<?php 
namespace WC_MMQ\Admin;

use WC_MMQ\Core\Base;

class Page_Loader extends Base
{

    public $main_slug = 'wcmmq-min-max-control';
    public $page_folder_dir;

    protected $is_pro;

    public function __construct()
    {
        $this->is_pro = defined( 'WC_MMQ_PRO_VERSION' );
        $this->page_folder_dir = $this->base_dir . 'admin/page/';
    }

    public function run()
    {
        add_action( 'admin_menu', [$this, 'admin_menu'] );
        add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );
        
    }


    
    public function main_page_html()
    {
        
        $main_page_file = $this->page_folder_dir . 'main-page.php';
        if( ! is_file( $main_page_file ) ) return;
        include $main_page_file;
    }
    
    

    public function admin_menu()
    {
        $capability = apply_filters( 'wcmmq_menu_capability', 'manage_woocommerce' );
    
        //This bellow line will removed
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

        add_submenu_page($this->main_slug, 'Documentation', 'Documentation', 'read','https://codeastrology.com/min-max-quantity/documentation/');
        if($this->is_pro){
            add_submenu_page($this->main_slug, 'Support', 'Support', 'read','https://codeastrology.com/my-support');
        }else{
            add_submenu_page($this->main_slug, 'Support & Buy', 'Support & Buy', 'read','https://codeastrology.com/downloads/min-max-step-control-wc/');
        }
        
    }

    public function admin_enqueue_scripts()
    {
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

        
        wp_register_script( 'wcmmq-admin-script', $this->base_url . 'assets/js/admin.js', array( 'jquery','select2' ), $this->dev_version, true );
        wp_enqueue_script( 'wcmmq-admin-script' );
        
        wp_register_style( 'ultraaddons-common-css', $this->base_url . 'assets/css/admin-common.css', false, $this->dev_version );
        wp_enqueue_style( 'ultraaddons-common-css' );

        wp_register_style( 'wcmmq_css', $this->base_url . 'assets/css/admin.css', false, $this->dev_version );
        wp_enqueue_style( 'wcmmq_css' );

        wp_register_style( 'wcmmq-new-admin', $this->base_url . 'assets/css/new-admin.css', false, $this->dev_version );
        wp_enqueue_style( 'wcmmq-new-admin' );
    }
    public function redirect_to_new_page()
    {
        wp_redirect(admin_url('admin.php?page=' . $this->main_slug));
    }
}