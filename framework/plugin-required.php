<?php 
namespace WC_MMQ\Framework;

use CA_Framework\App\Notice as Notice;
use CA_Framework\App\Require_Control as Require_Control;

include_once __DIR__ . '/ca-framework/framework.php';

if( ! class_exists( 'Plugin_Required' ) ){

    class Plugin_Required
    {
        public static $stop_next = 0;
        public function __construct()
        {
            
        }
        public static function fail()
        {

            /**
             * Getting help from configure
             * $config = get_option( 'wpt_configure_options' );
        $disable_plugin_noti = !isset( $config['disable_plugin_noti'] ) ? true : false;
             */

            $r_slug = 'woocommerce/woocommerce.php';
            $t_slug = 'woo-min-max-quantity-step-control-single/wcmmq.php';
            $req_wc = new Require_Control($r_slug,$t_slug);
            $req_wc->set_args(['Name'=>'WooCommerce'])
            ->set_download_link('https://wordpress.org/plugins/woocommerce/')
            ->set_this_download_link('https://wordpress.org/plugins/woo-min-max-quantity-step-control-single/')
            // ->set_message("this sis is  sdisd sdodso")
            ->set_required()
            ->run();
            $req_wc_next = $req_wc->stop_next();
            self::$stop_next += $req_wc_next;
            
            if( ! $req_wc_next ){
                self::display_notice();
                // self::display_common_notice();
            }

            return self::$stop_next;
        }

        /**
         * Normal Notice for Only Free version
         *
         * @return void
         */
        public static function display_notice()
        {
            
            if( ! is_admin() ) return;

            //Today: 14.2.2024 - 1707890302 and added 20 days seccond - 1664000 (little change actually)
            if( time() > ( 1707890302 + 1664000 ) ) return;
            if( defined( 'WC_MMQ_PRO_VERSION' ) ){
                self::display_notice_on_pro();
                return;
            }
            
            $temp_numb = rand(1,15);


            $coupon_Code = 'SPECIAL_OFFER_FEB_2024';
            $target = 'https://codeastrology.com/min-max-quantity/pricing/?discount=' . $coupon_Code . '&campaign=' . $coupon_Code . '&ref=1&utm_source=Default_Offer_LINK';
            $my_message = 'Control and Customized min max and step with <b>Discount</b> for <b>Min Max Quantity & Step Control for WooCommerce Pro</b>';
            $offerNc = new Notice('wcmmq_'.$coupon_Code.'_offer');
            $offerNc->set_title( 'SPECIAL OFFER UPTO 70% 🍋 🍌' )
            ->set_diff_limit(3)
            ->set_type('offer')
            ->set_img( WC_MMQ_BASE_URL. 'assets/images/copoun-min-max.png')
            ->set_img_target( $target )
            ->set_message( $my_message )
            ->add_button([
                'text' => 'Click Here to get Discount',
                'type' => 'success',
                'link' => $target,
            ]);

            $offerNc->add_button([
                'text' => 'Unlimited Access(Lifetime) with Discount',
                'type' => 'default',
                'link' => 'https://codeastrology.com/checkout?edd_action=add_to_cart&download_id=6557&edd_options%5Bprice_id%5D=6&discount=' . $coupon_Code,
            ]);
            
            if($temp_numb == 5) $offerNc->show();
            
                

        }

        private static function display_notice_on_pro()
        {

            $temp_numb = rand(1, 35);
            $coupon_Code = 'SPECIAL_OFFER_' . date('M_Y');
            $target = 'https://codeastrology.com/downloads/?discount=' . $coupon_Code . '&campaign=' . $coupon_Code . '&ref=1&utm_source=Default_Offer_LINK';
            $my_message = 'Speciall Discount on All CodeAstrology Products'; 
            $offerNc = new Notice('wcmmq_'.$coupon_Code.'_offer');
            $offerNc->set_title( 'SPECIAL OFFER 🍌' )
            ->set_diff_limit(10)
            ->set_type('offer')
            ->set_img( WC_MMQ_BASE_URL. 'assets/images/brand/social/web.png')
            ->set_img_target( $target )
            ->set_message( $my_message )
            ->add_button([
                'text' => 'Get WooCommerce Product with Discount',
                'type' => 'success',
                'link' => $target,
            ]);

            if($temp_numb == 35) $offerNc->show();
        }

        /**
         * Common Notice for Product table, where no need Pro version.
         *
         * @return void
         */
        private static function display_common_notice()
        {

        }
    }
}

