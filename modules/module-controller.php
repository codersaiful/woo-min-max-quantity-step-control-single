<?php
namespace WC_MMQ\Modules;
class Module_Controller
{
    public $parent_menu = 'options-general.php';
    public $option_key = 'wcmmq_disable_modules';

    public $menu_title;
    private $modules = array();

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
        $modules = array(
            'gutten_block' => array(
                'key'   => 'gutten_block',
                'name'  =>  __( 'Min Max in Block', 'wcmmq' ),
                'desc'  =>  __( 'For Qutenberge Block product require it. If not used. Deactivate this Module.', 'wcmmq' ),
                'status'=>  'on',
            ),
            'gutten_blocks' => array(
                'key'   => 'gutten_block',
                'name'  =>  __( 'Min Max in Block', 'wcmmq' ),
                'desc'  =>  __( 'For Qutenberge Block product require it. If not used. Deactivate this Module.', 'wcmmq' ),
            ),  'status'=>  'on',
        );
        $this->modules = apply_filters( 'wcmmq_module_arr', $modules );

        add_action( 'admin_menu', [$this, 'admin_menu'] );
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

}
