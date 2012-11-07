<?php

    use Zend\Config\Reader\Yaml;
    require_once 'lib/utils.php';

    class Config {

        static function getConfig() {
            $reader = new Zend\Config\Reader\Yaml();
            $config = $reader->fromFile('config.yaml');

            return $config;
        }

        public function setConfig($config) {
            # code...
        }

    }