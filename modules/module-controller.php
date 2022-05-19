<?php
namespace WC_MMQ\Modules;
class Module_Controller
{

    public $prefix = 'wcmmq_';

    public $parent_menu = 'options-general.php';
    private $option_key = 'disable_modules';
    public $options;

    public $menu_title;
    private $modules = array();
    private $active_module_key = 'active_modules';
    private $active_module = array();



    private $dir = __DIR__;

    /**
     * For Instance
     *
     * @var Object 
     * 
     * @since 2.7.1
     */
    private static $_instance;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 2.7.1
     *
     * @access public
     * @static
     *
     * @return WC_MMQ An instance of the class.
     */
    public static function instance() {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->menu_title = __( 'Min Max Modules', 'wcmmq' );
        
        $module_item = array(
            'gutten_block' => array(
                'key'   => 'gutten_block',
                'name'  =>  __( 'Guttenberg Block', 'wcmmq' ),
                'desc'  =>  __( 'For Qutenberge Block product require it. If not used. Deactivate this Module.', 'wcmmq' ),
                'status'=>  'on',
                'dir'   =>  __DIR__,
            ),
            'elementor_blok' => array(
                'key'   => 'elementor_blok',
                'name'  =>  __( 'Elementor Block', 'wcmmq' ),
                'desc'  =>  __( 'For Qutenberge Block product require it. If not used. Deactivate this Module.', 'wcmmq' ),
                'status'=>  'on',
            ) 
        );
        $module_item = apply_filters( 'wcmmq_module_item', $module_item );

        
        
        $this->modules = apply_filters( 'wcmmq_module_arr', array(
            'data'      => array(
                'default' => 'on',
            ),
            'items'     => $module_item
        ) );
        

        $this->option_key = $this->prefix . $this->option_key;
        $this->active_module_key = $this->prefix . $this->active_module_key;
        add_action( 'admin_menu', [$this, 'admin_menu'] );

       foreach( $this->get_active_modules() as $key_modl=>$modl ){
           $file_dir = ! empty( $modl['dir'] ) ? $modl['dir'] : $this->dir;
           $file_dir = trailingslashit($file_dir);
           $file_name = $key_modl;
           $file = $file_dir . 'files/' . $file_name .'.php'; // '/'. $file_name . 
           if( is_file( $file ) ){
            include_once $file; 
           }
           
       }
        
    }


    public function update( $values = array() )
    {
        // var_dump( $values, $this->get_option() );
        if( empty( $values ) ){
            $values = array_map( function($arr){
                return 'off';
            },$this->get_option() );
        }
        update_option($this->option_key,$values);
        // $active_module = array();
        // foreach( $this->get_module_list() as $key => $module ){
        //     if( $module['status'] === 'on' ){
        //         $active_module[$key] = $module;
        //     }
        // }
        // $this->purefy_module();
        // var_dump( $this->get_module_list(), $this->options );
        return $this;

    }

    public function get_option()
    {

        $option = get_option( $this->option_key );
        if( ! empty( $option ) && is_array( $option ) ) return $option;
        $def_option = array_map(function($arr){
            return $arr['status'];
        }, $this->modules['items']);
        return $def_option;

    }

    public function purefy_module()
    {
        $this->options = $this->get_option();
        
        if( empty( $this->modules['items'] ) || ! is_array( $this->modules['items'] ) ) return;

        foreach( $this->modules['items'] as $key=>$val ){
            $this->modules['items'][$key]['status'] = $this->options[$key] ?? 'off';
        }

        return $this;
    }

    public function admin_menu()
    {
        $capability = apply_filters( 'wcmmq_menu_capability', 'manage_woocommerce', 'module_page' );
        add_submenu_page( $this->parent_menu, $this->menu_title, $this->menu_title, $capability, 'wcmmq_modules', [$this, 'module_page'] );
    }

    public function module_page()
    {
        include_once __DIR__ . '/module-page.php';
    }


    public function get_module_list()
    {
        $this->purefy_module();
        return $this->modules['items'] ?? array();
    }


    public function get_active_modules()
    {
        $active = array_filter($this->get_module_list(),function($arr){
            if( $arr['status'] == 'on' ) return $arr;
        });

        return is_array( $active ) ? $active : array();
    }
    
    public function get_module_info()
    {
        return $this->modules['data'] ?? array();
    }
    

}
