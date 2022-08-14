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
                if( defined( 'WC_MMQ_PRO_VERSION' ) ) return;
                /**
                 * small notice for pro plugin,
                 * charect:
                 * 10 din por por
                 * 
                 */

                // $small_notc = new Notice('small2');
                // $small_notc->set_message(sprintf( __( '<b>Product Table for Woocommerce (Woo Product Table)</b>: lots of special feature waiting for you. %s.', 'wpt_pro' ), "<a href='https://wooproducttable.com/pricing/'>Get Premium</a>" ));
                // $small_notc->set_diff_limit(7);
                // $small_notc->show();


                /**
                 * Offer Hanndle
                 */
                $target = 'https://codeastrology.com/min-max-quantity//?discount=OfferAug&campaign=OfferAug&utm_source=Offer_LINK';
                $demo_link = 'https://codeastrology.com/min-max-quantity/product/album/?campaign=OfferAug&utm_source=Offer_LINK';
                $my_message = 'Have you enjoyed using <b>Min Max Quantity & Step Control for WooCommerce</b> Plugin? Get up to 60% OFF your purchase. [FOR LIMITED TIME] <a class="ca-button" href="https://codeastrology.com/min-max-quantity//?discount=OfferAug&amp;campaign=OfferAug&amp;utm_source=Offer_LINK" target="_blank">Get Discount &rarr;</a>';
                $offerNc = new Notice('offerAug22');
                $offerNc->set_title( 'Min Max Quantity & Step Control for WooCommerce ::: Discount UPTO 60%' )
                ->set_diff_limit(10)
                ->set_type('offer')
                ->set_img( WC_MMQ_BASE_URL. 'assets/images/offer/discount.png')
                ->set_img_target( $target )
                ->set_message( $my_message )
               /*  ->add_button([
                    'text' => 'Get Discount',
                    'type' => 'primary',
                    'link' => $target,
                ]) */;
                if( method_exists($offerNc, 'set_location') ){
                    $offerNc->set_location('wcmmq_offer_here'); //wpt_premium_image_bottom
                }
                $offerNc->show();
                
                
                

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

