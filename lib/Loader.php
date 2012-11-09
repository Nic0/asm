<?php

    require_once 'Zend/Loader/StandardAutoloader.php';
    require_once '../lib/AsmConfig.php';
    use Zend\Loader\StandardAutoloader;

    class Loader {

        static public function load () {
            $autoLoader = new StandardAutoloader(array(
                'prefixes' => array(
                    'MyVendor' => __DIR__ . '/MyVendor',
                ),
                'namespaces' => array(
                    'Asm' => __DIR__ . '/',
                ),
                'fallback_autoloader' => true,
            ));

            // register our StandardAutoloader with the SPL autoloader
            $autoLoader->register();

            require_once '../vendor/Twig/Autoloader.php';
            Twig_Autoloader::register();

        }
    }