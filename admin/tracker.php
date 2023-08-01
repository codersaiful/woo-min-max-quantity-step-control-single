<?php
namespace WC_MMQ\Admin;

use WC_MMQ\Core\Base;
use WC_MMQ\Modules\Module_Controller;

class Tracker extends Base
{
    protected $transient = 'wcmmq_transient_trak';
    protected $plugin_name = 'Min Max';
    protected $plugin_version = WC_MMQ_VERSION;
    

    protected $option_key = 'wcmmq_trak_optin';

    protected $optin_bool;
    public $tracker_url = 'http://wptheme.cm/wp-json/tracker/v1/track';
    public function __construct()
    {
        $this->optin_bool = get_option( $this->option_key );
    }

    public function run()
    {

    $user_id = get_current_user_id();
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $plugin_version = '1.0'; // Replace this with your actual plugin version

    $other = [];
    $other['plugin_version'] = $this->plugin_version;

    $data = [
        'plugin' => $user_id,
        'site' => $ip_address,
        'email' => 'saiful@codeastrology.com',
        'other' => $plugin_version,
        'track_time' => time(),
        'track_count' => 2,
    ];

    $api_url = $this->tracker_url; // Replace this with your actual API endpoint
    var_dump($api_url);
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
}