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

                //after 10 days, offer will closed | Today: 11 Sept, 2023
                if(time() > 1694587433 + 864000) return;
                if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
                
                $temp_numb = rand(1,5);
                /**
                 * small notice for pro plugin,
                 * charect:
                 * 10 din por por
                 * 
                 */

                //  $small_notc = new Notice('wcmmq-WP20-notice-s');
                //  $small_notc->set_message(sprintf( __( "Are you enjoying <b>%s</b>? <b>COUPON CODE: <i>WP20</i> - up to 60%% OFF</b> %s.", 'wcmmq' ),"<a href='https://wordpress.org/plugins/woo-min-max-quantity-step-control-single/' target='_blank'>Min Max Quantity & Step Control by CodeAstrology</a>", "<a href='https://codeastrology.com/coupons/?campaign=SPECIAL60F10DAYS&ref=1&utm_source=Default_Offer_LINK' target='_blank'>Click Here</a>" ));
                //  $small_notc->set_diff_limit(10);
                //  if( method_exists($small_notc, 'set_location') ){
                //      $small_notc->set_location('wpt_premium_image_top'); //wpt_premium_image_bottom
                //  }
                //  if($temp_numb == 2) $small_notc->show();

                /**
                 * Offer Hanndle
                 */
                $coupon_Code = 'CA60PERCENT';
                $target = 'https://codeastrology.com/min-max-quantity/pricing/?discount=' . $coupon_Code . '&campaign=' . $coupon_Code . '&ref=1&utm_source=Default_Offer_LINK';
                $my_message = '<b><i>COUPON CODE: ' . $coupon_Code . ' - up to 60% OFF</i></b> A coupon code for you for <b>Min Max Control</b> Plugin';
                $offerNc = new Notice('wcmmq_'.$coupon_Code.'_offer');
                $offerNc->set_title( 'SPECIAL OFFER for 10 days' )
                ->set_diff_limit(3)
                ->set_type('offer')
                ->set_img( WC_MMQ_BASE_URL. 'assets/images/min-max-logo.png')
                ->set_img_target( $target )
                ->set_message( $my_message )
                ->add_button([
                    'text' => 'Full Pricing',
                    'type' => 'success',
                    'link' => $target,
                ]);
                $offerNc->add_button([
                    'text' => 'Unlimited Access(Lifetime)',
                    'type' => 'default',
                    'link' => 'https://codeastrology.com/checkout?edd_action=add_to_cart&download_id=6557&edd_options%5Bprice_id%5D=6&discount=' . $coupon_Code,
                ]);
                $offerNc->add_button([
                    'text' => 'Unlimited Access(Yearly)',
                    'type' => 'offer',
                    'link' => 'https://codeastrology.com/checkout?edd_action=add_to_cart&download_id=6557&edd_options%5Bprice_id%5D=3&discount=' . $coupon_Code,
                ]);
                $offerNc->add_button([
                    'text' => 'All Products',
                    'type' => 'error',
                    'link' => 'https://codeastrology.com/downloads//?discount=' . $coupon_Code,
                ]);
                if( method_exists($offerNc, 'set_location') ){
                    // $offerNc->set_location('wpt_offer_here'); //wpt_premium_image_bottom
                }
                if($temp_numb == 5) $offerNc->show();
                
                

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

