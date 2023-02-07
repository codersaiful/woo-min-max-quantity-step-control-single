<?php
namespace WC_MMQ\Admin\Adm_Inc\Settings;

/**
 * For adding new tab in WooCommerce Setting page
 * 
 * Still we didn't take any diceission for this class
 * 
 * @since 3.6.0
 * 
 * I have taken help from
 * @link https://www.speakinginbytes.com/2014/07/woocommerce-settings-tab/
 * 
 */
class WC_Tab
{
    public $tab_key = 'ca_min_max';
    public function run()
    {
        //Showing new tab
        add_filter('woocommerce_settings_tabs_array',[$this, 'wc_tab_minmax'], 50);

        
        //Displaying Option fields and content
        add_action('woocommerce_settings_tabs_' . $this->tab_key,[$this, 'setting_tab']);
        // add_action('woocommerce_settings_tabs_settings_tab_demo' . $this->tab_key,[$this, 'setting_tab']);

        add_action( 'woocommerce_update_options_' . $this->tab_key, [$this, 'update_settings'] );

    }

    public function wc_tab_minmax( $tab_array )
    {
        $tab_array[$this->tab_key] = __( 'Min Max Step', 'wcmmq' );
        return $tab_array;
    }

    /*
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public function setting_tab()
    {
        
        woocommerce_admin_fields( $this->get_settings() );
    }

    /*** 
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public function update_settings() {
        woocommerce_update_options( $this->get_settings() );
    }

    /** 
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public function get_settings()
    {
        $settings = array(
            'section_title' => array(
                'name'     => __( 'Section Title', 'woocommerce-settings-tab-demo' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_demo_section_title'
            ),
            'title' => array(
                'name' => __( 'Title', 'woocommerce-settings-tab-demo' ),
                'type' => 'text',
                'desc' => __( 'This is some helper text', 'woocommerce-settings-tab-demo' ),
                'id'   => 'wc_settings_tab_demo_title'
            ),
            'description' => array(
                'name' => __( 'Description', 'woocommerce-settings-tab-demo' ),
                'type' => 'textarea',
                'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'woocommerce-settings-tab-demo' ),
                'id'   => 'wc_settings_tab_demo_description'
            ),
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_demo_section_end'
            )
        );

        return apply_filters( 'wc_settings_tab_' . $this->tab_key . '_settings', $settings );

    }
}