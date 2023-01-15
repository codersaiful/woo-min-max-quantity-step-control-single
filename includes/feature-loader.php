<?php
namespace WC_MMQ\Includes;

use WC_MMQ;
use WC_MMQ\Includes\Features\Quantiy_Archive;

class Feature_Loader
{

    public static $options;
    public static function run()
    {
        self::$options = WC_MMQ::getOptions();
        $quantiy_archive = self::$options['quantiy_box_archive'] ?? false;
        if( ! empty( $quantiy_archive ) && $quantiy_archive == 1 ){
            $quantiy_archive_obj = new Quantiy_Archive();
            $quantiy_archive_obj->run();
        }
    }
}