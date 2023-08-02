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
    /**
     * jetar uppor vitti kore mulot online dekhabe
     * ami ekhane 1 ghonta debo
     * 1 hour = 3600 second
     * half hour = 1800 second
     *
     */
    protected $transient_exp = 1800; // in second // when test used 60
    
    public $_domain = 'http://edm.ultraaddons.com'; //Don't use slash at the end of the link. eg: http://wptheme.cm or: http://edm.ultraaddons.com
    public $tracker_url;

    public $route = '/wp-json/tracker/v1/track';
    public function __construct()
    {
        $this->tracker_url = $this->_domain . $this->route;
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
        
        global $wpdb,$wp_version;
        $other = [];
        $other['plugin_version'] = $this->plugin_version;
        $other['active_plugins'] = $this->active_plugins();
        // $other['php_version'] = phpversion();
        $other['php_version'] = PHP_VERSION;
        
        $other['wp_version'] = $wp_version;
        $other['mysql_version'] = $wpdb->db_version();
        $other['wc_version'] = WC()->version;

        $data = [
            'plugin' => $this->plugin_name,
            'site' => site_url(),
            'site_title' => get_bloginfo('name'),
            'email' => '',//$user->user_email
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