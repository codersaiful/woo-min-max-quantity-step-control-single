<?php
namespace WC_MMQ\Api;

use WP_REST_Server;
use WP_REST_Controller;

class Api extends WP_REST_Controller{
    public $route;
    public function __construct()
    {
        $this->route = new Route_Control();

        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    public function register_routes()
    {
        $this->route->register();
        
    }
}