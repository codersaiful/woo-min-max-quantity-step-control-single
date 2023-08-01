<?php
namespace WC_MMQ\Admin;

use WC_MMQ\Core\Base;
use WC_MMQ\Modules\Module_Controller;

class Tracker extends Base
{
    protected $transient_key = 'wcmmq_transient_trak';
    protected $transient;
    
    protected $plugin_name = 'Min Max Control';
    protected $plugin_version = WC_MMQ_VERSION;
    

    protected $option_key = 'wcmmq_trak_optin';

    protected $optin_bool;
    protected $transient_exp = 25; // in second
    public $tracker_url = 'http://wptheme.cm/wp-json/tracker/v1/track';
    public function __construct()
    {
        $this->optin_bool = get_option( $this->option_key );
        $this->transient = get_transient( $this->transient_key );
        
    }

    public function run()
    {
        if( $this->transient ) return;
        if( function_exists('current_user_can') && ! current_user_can('administrator') ) return;
        
        set_transient($this->transient_key, 'sent', $this->transient_exp);
        
        $user = wp_get_current_user();
        $theme = wp_get_theme();
        $themeName = $theme->Name;
        
        
        $other = [];
        $other['plugin_version'] = $this->plugin_version;
        $other['active_plugins'] = $this->active_plugins();
        
        $data = [
            'plugin' => $this->plugin_name,
            'site' => site_url(),
            'site_title' => get_bloginfo('name'),
            'email' => $user->user_email,
            'theme' => $themeName,
            'other' => json_encode($other),
        ];

        $api_url = $this->tracker_url; // Replace this with your actual API endpoint

        // return;
        // Send data to the tracking API using the WordPress HTTP API
        wp_remote_post( $api_url, array(
            'method' => 'POST',
            'timeout' => 15,
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode( $data ),
        ) );
    }

    /**
     * List of Active plugins.
     *
     * @return array
     */
    private function active_plugins(){
        $active_plugins = get_option( 'active_plugins', array() );
    
        // Return an array of plugin names (without file paths)
        $plugin_names = array_map( 'plugin_basename', $active_plugins );
    
        return $plugin_names;
    }
}