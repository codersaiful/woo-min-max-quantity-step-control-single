<?php
namespace WC_MMQ\Admin\Adm_Inc;

class Plugin_Installer
{
    public function run()
    {
        add_action('admin_init', [$this,'handle_install']);
    }
    public function handle_install()
    {
        if (isset($_POST['install_plugin']) && check_admin_referer('pssg_install_plugin_nonce', 'pssg_install_plugin_nonce')) {
            // URL to the zip file of the custom plugin
            $plugin_zip_url = 'https://example.com/path/to/your-plugin.zip';
    
            // Install and activate the plugin
            $result = $this->install_zip( $plugin_zip_url );
            if (is_wp_error($result)) {
                // Handle error, if any
                echo 'Error installing the plugin: ' . esc_html($result->get_error_message());
            } else {
                // Redirect to your admin page menu
                wp_redirect(admin_url('admin.php?page=wcmmq-product-quick-edit'));
                exit;
            }
        }
    }
    public function install_zip()
    {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());
        $install_result = $upgrader->install($plugin_zip_url);

        return $install_result;
    }
}