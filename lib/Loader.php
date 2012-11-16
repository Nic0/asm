<?php

    require_once 'Zend/Loader/StandardAutoloader.php';
    require_once '../lib/AsmConfig.php';

    use Zend\Loader\StandardAutoloader;

    /**
     * @brief Chargement et initialisation de bibliothèque utile à toutes requète
     *
     * La classe ne comprends qu'un point d'entrée, la méthode static `load`
     *
     * On retrouve le chargement de:
     *
     * - Zend
     * - Twig
     *
     */
    class Loader {

        static public function load () {

            /**
             * chargement propre à Zend\Loader\StandarAutoloader, voir la
             * documentation pour plus de détails.
             * @var StandardAutoloader
             */
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

            // Chargement de Twig
            require_once '../vendor/Twig/Autoloader.php';
            Twig_Autoloader::register();

        }
    }