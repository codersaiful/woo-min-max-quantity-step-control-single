<?php

namespace WC_MMQ;

if( ! class_exists( 'WC_MMQ\Autoloader' ) ){

    /**
     * Autoloader: All class will call from here by AutoLoader
     *
     * @author Saiful Islam<codersaiful@gmail.com>
     * @since 1.0.0.8
     * @package CA Framework
     * @link https://www.php.net/manual/en/language.oop5.autoload.php Autoloader Function
     */
    class Autoloader
    {
        

        /**
         * Run autoloader.
         *
         * Register a function as `__autoload()` implementation.
         *
         * @since 1.0.0
         * @access public
         * @static
         */
        public static function run() {
            spl_autoload_register([ __CLASS__ , 'autoload' ]);
        }

        /**
         * Autoload.
         *
         * For a given class, check if it exist and load it.
         *
         * @since 1.0.0.8
         * @access private
         * @static
         *
         * @param string $class Class name.
         */
        private static function autoload( $class ) {
            
            if (0 !== strpos( $class, __NAMESPACE__ ) ) {
                return;
            }


            $filename = strtolower(
                    preg_replace(
                            ['/\b' . __NAMESPACE__ . '\\\/', '/_/', '/\\\/'], ['', '-', DIRECTORY_SEPARATOR], $class
                    )
            );

            $filename = __DIR__. '/' . $filename . '.php';

            $filename = realpath( $filename );

            if ( is_readable( $filename ) ) {
                require_once $filename;
            }

        }

    }

    Autoloader::run();
}

