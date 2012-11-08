<?php

    require_once 'Zend/Loader/StandardAutoloader.php';
    use Zend\Loader\StandardAutoloader;
    use Zend\Config\Reader\Yaml;
    use Zend\Loader\loadFile;

    class Loader {

        static public function load() {
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

        }
    }