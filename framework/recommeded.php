<?php 
namespace WC_MMQ\Framework;

use CA_Framework\App\Notice as Notice;
use CA_Framework\App\Require_Control as Require_Control;

include_once __DIR__ . '/ca-framework/framework.php';

class Recommeded
{
    public static function check()
    {
        $this_plugin = __( 'CodeAstrology Min Max', 'wpt_pro' );
        
        $mmp_req_slug = 'woo-product-table/woo-product-table.php';
        $mmp_tar_slug = WC_MMQ_PLUGIN_BASE_FILE;
        $req_mmp = new Require_Control($mmp_req_slug,$mmp_tar_slug);
        $req_mmp->set_args( ['Name' => 'Product Table for WooCoomerce by CodeAstrology'] )
        ->set_download_link('https://wordpress.org/plugins/woo-product-table/')
        ->set_this_download_link('https://wordpress.org/plugins/woo-min-max-quantity-step-control-single/');
        $mmp_message = __('%s Product Table plugin helps you to display your WooCommerce products in a searchable table layout with filters. Add a table on any page or post via a shortcode. You can create tables as many as you want.','wcmmq');
        $wpt_link = "<a href='https://wooproducttable.com/' target='_blank'>(Woo Product Table)</a>";
        $mmp_message = sprintf($mmp_message, $wpt_link);
        $req_mmp->set_message($mmp_message);
        $req_mmp->get_full_this_plugin_name($this_plugin);
        // var_dump(method_exists($req_mmp, 'set_location'),$req_mmp);
        // ->set_required();
        if( method_exists($req_mmp, 'set_location') ){
            $req_mmp->set_location('wcmmq_offer_here'); //wpt_premium_image_bottom
            $req_mmp->run();

            $req_mmp->set_location('wcmmq_form_bottom'); //wpt_premium_image_bottom
            $req_mmp->run();
        }


        $pmb_req_slug = 'wc-quantity-plus-minus-button/init.php';
        $pmb_tar_slug = WC_MMQ_PLUGIN_BASE_FILE;
        $req_pmb = new Require_Control($pmb_req_slug,$pmb_tar_slug);
        $req_pmb->set_args( ['Name' => 'Quantity Plus Minus Button for WooCommerce'] )
        ->set_download_link('https://wordpress.org/plugins/wc-quantity-plus-minus-button/')
        ->set_this_download_link('https://wordpress.org/plugins/woo-min-max-quantity-step-control-single/');
        $pmb_message = __('If you want to set plus minus button for your Quantity Box, you can Install this plugin. If already by your theme, ignore it.','wpt_pro');
        $req_pmb->set_message($pmb_message);
        $req_pmb->get_full_this_plugin_name($this_plugin);
        // ->set_required();
        if( method_exists($req_pmb, 'set_location') ){
            $req_pmb->set_location('wcmmq_offer_here');
            $req_pmb->run();
            $req_pmb->set_location('wcmmq_form_bottom');
            $req_pmb->run();
        }

        $pmb_req_slug = 'ultraaddons-elementor-lite/init.php';
        $pmb_tar_slug = WC_MMQ_PLUGIN_BASE_FILE;
        $req_pmb = new Require_Control($pmb_req_slug,$pmb_tar_slug);
        $req_pmb->set_args( ['Name' => 'UltraAddons - Elementor Addons'] )
        ->set_download_link('https://wordpress.org/plugins/ultraaddons-elementor-lite/')
        ->set_this_download_link('https://wordpress.org/plugins/woo-product-table/');
        $pmb_message = __('There are many WooCommerce Widget available at UltraAddons. You can Try it. Just Recommended','wpt_pro');
        $req_pmb->set_message($pmb_message);
        $req_pmb->get_full_this_plugin_name($this_plugin);

        if( method_exists($req_pmb, 'set_location') && did_action( 'elementor/loaded' ) ){
            $req_pmb->set_location('wcmmq_form_bottom'); //wcmmq_form_bottom
            $req_pmb->run();
        }
    }
}