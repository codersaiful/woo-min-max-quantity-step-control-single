<?php
namespace WC_MMQ\Includes;

use WC_MMQ;
use WC_MMQ\Core\Base;

class Min_Max_Controller extends Base
{

    public $options;


    public function __construct()
    {
        $this->options = WC_MMQ::getOptions();
    }
}