<?php 
namespace WC_MMQ\Admin;

use WC_MMQ;
use WC_MMQ\Core\Base;
use WC_MMQ\Admin\Page_Loader;
use WC_MMQ\Admin\Tracker;
use WC_MMQ\Admin\Adm_Inc\Settings\Settings_Loader;
use WC_MMQ\Admin\Adm_Inc\Plugin_Deactive\Deactive_Form;

class Admin_Loader extends Base{
    public function __construct(){
        $deactive_form = new Deactive_Form();
        $deactive_form->run();

        $main_page = new Page_Loader();
        $main_page->run();

        

        // $settings = new Settings_Loader();
        // $settings->run();

        add_action('admin_init', [$this, 'admin_init']);
    }

    public function admin_init(){
        /**
         * Tracker Enable Only Based on Customer Approval
         * You able to disbale/Enable from
         * Dashboard -> Min Max Control -> Support & Tracker -> Tracker
         * 
         * @since 4.5.8
         */
        $tracker = WC_MMQ::getOption('tracker');
        if( $tracker ){
            $tracker = new Tracker();
            $tracker->run();
        }
        
    }
}