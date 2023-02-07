<?php 
namespace WC_MMQ\Admin;

use WC_MMQ\Core\Base;
use WC_MMQ\Admin\Adm_Inc\Settings\Settings_Loader;
use WC_MMQ\Admin\Adm_Inc\Plugin_Deactive\Deactive_Form;

class Admin_Loader extends Base{
    public function __construct(){
        $deactive_form = new Deactive_Form();
        $deactive_form->run();

        $settings = new Settings_Loader();
        $settings->run();
    }
}