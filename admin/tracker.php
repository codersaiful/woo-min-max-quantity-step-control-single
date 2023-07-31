<?php
namespace WC_MMQ\Admin;

use WC_MMQ\Core\Base;
use WC_MMQ\Modules\Module_Controller;

class Tracker extends Base
{
    public $tracker_url = 'http://wptheme.cm/wp-json/tracker/v1';
    public function __construct()
    {
        // var_dump($this);
    }

    public function run()
    {
    
    $user_id = get_current_user_id();
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $plugin_version = '1.0'; // Replace this with your actual plugin version

    $data = array(
        'user_id' => $user_id,
        'ip_address' => $ip_address,
        'plugin_version' => $plugin_version,
        // Add more data fields as needed
    );

    $api_url = $this->tracker_url; // Replace this with your actual API endpoint
    var_dump(json_encode( $data ));
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