<?php
namespace WC_MMQ\Api;

use WP_REST_Controller;

class Route_Control extends WP_REST_Controller{
    protected $namespace;
    protected $rest_base;
    protected $data;

    public function __construct()
    {
        $this->namespace = 'wcmmq/v1';
        $this->rest_base = 'settings/(?P<id>\d+)';
        $this->data = get_option( WC_MMQ_KEY );
    }

    public function callback_permission( $request)
    {
        // $data = $request->get_json_params();

    // Retrieve and validate the nonce
    // $nonce = $data['nonce'];
    // var_dump($data);
        // $nonce = $request->get_header('X-WP-Nonce');
        // $datasss = $request->get_headers();
        // var_dump($nonce);
        return true;
    }

    public function register()
    {

        register_rest_route($this->namespace, $this->rest_base,[
            [
                'method'    => \WP_REST_Server::READABLE,
                'callback'  => [$this, 'my_data'],
                'permission_callback' => [ $this, 'callback_permission' ],
                'args'                => $this->get_endpoint_args_for_item_schema(true )
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'create_items' ],
                'permission_callback' => [ $this, 'callback_permission' ],
                'args'                => $this->get_endpoint_args_for_item_schema(true )
            ],
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [ $this, 'create_edit' ],
                'permission_callback' => [ $this, 'callback_permission' ],
                'args'                => $this->get_endpoint_args_for_item_schema(true )
            ]
        ]);
    }

    public function my_data($request)
    {

        $response = [
            'name' => 'Saiful' . rand(454, 7878787),
            'email' => 'codersaiful@gmail.com',
            'method' => 'READABLE',
        ];

        $response = $this->data;
        return rest_ensure_response( $response );
    }
    public function create_items($request)
    {

        

        $response = [
            'name' => 'Saiful' . rand(454, 7878787),
            'email' => 'codersaiful@gmail.com',
            'method' => 'CREATABLE',
        ];
        return rest_ensure_response( $response );
    }
    public function create_edit($request)
    {
        $response = [
            'name' => 'Saiful' . rand(454, 7878787),
            'email' => 'codersaiful@gmail.com',
            'method' => 'EDITABLE',
        ];
        return rest_ensure_response( $response );
    }
}