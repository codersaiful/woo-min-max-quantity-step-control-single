<?php
namespace WC_MMQ\Admin\Adm_Inc\Settings;

class Menu_Control
{
    public function run()
    {
        add_filter('woocommerce_settings_tabs_array',[$this, 'wc_tab_minmax'], 100);
    }

    public function wc_tab_minmax( $tab_array )
    {
        // var_dump($tab_array);
        $tab_array['wc-min-max'] = __( 'Min Max Step', 'wcmmq' );
        return $tab_array;
    }
}