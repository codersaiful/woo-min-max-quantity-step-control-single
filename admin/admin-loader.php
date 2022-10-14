<?php 
namespace WC_MMQ\Admin;

use WC_MMQ\Core\Base;
use WC_MMQ\Admin\Adm_Inc\Deactive_Form;
class Admin_Loader extends Base{
    public function __construct(){
        $deactive_form = new Deactive_Form();
        $deactive_form->run();
    }
}