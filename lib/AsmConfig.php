<?php

    use Zend\Config\Reader\Yaml;
    use Zend\Config\Config;
    require_once 'lib/utils.php';

    class AsmConfig {

        static function getConfig() {
            $reader = new Yaml();
            $configArray = $reader->fromFile('config.yaml');
            $config = new Config($configArray);
            return $config;
        }

        public function setConfig($config) {
            # code...
        }

    }