<?php
namespace WC_MMQ\Modules;
class Module_Controller
{

    public $prefix = 'wcmmqs_';

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
        
        $modules_data = array(
            'data'      => array(
                'default' => 'on',
            ),
            'items'     => array(
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
            )
        );

        
        $this->modules = apply_filters( 'wcmmq_module_arr', $modules_data );
        $this->get_active_modules();
        

        $this->option_key = $this->prefix . $this->option_key;
        $this->active_module_key = $this->prefix . $this->active_module_key;
        add_action( 'admin_menu', [$this, 'admin_menu'] );
    }


    public function update( $values = array() )
    {
        if( empty( $values ) ){
            $values = array_map( function($arr){
                return 'off';
            },$this->get_option() );
        }
        update_option($this->option_key,$values);
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
        var_dump($this->get_option()); //$this->get_module_list()
    }
    
    public function get_module_info()
    {
        return $this->modules['data'] ?? array();
    }
    

}
