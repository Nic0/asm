<?php

    use Zend\Config\Reader\Yaml;
    use Zend\Config\Config;
    require_once '../lib/utils.php';

    /**
     * @brief Prise en main de la configuration
     *
     * La configuration est prise dans le fichier `config.yaml`, et se trouve
     * dans un format YAML.
     *
     * La configuration est géré avec `Zend\Config\*`. Il permet notamment de
     * convertire la configuration en un objet.
     *
     */
    class AsmConfig {

        public $filename = '../config.yaml'; /** @brief Nom du fichier de config */

        /**
         * @brief Permet d'obtenir la configuration
         * @return object La configuration sous forme d'objet.
         *
         * Pour un élément de configuration comme suit, et afin de récupérer l'host :
         *
         * ~~~~~~~~~~~~~~
         * mysql:
         *    zabbix:
         *        driver:   Pdo_Mysql
         *        host:     mysql.crbn.intra
         * ~~~~~~~~~~~~~~~
         *
         * Les éléments sont accessible comme suit:
         *
         * ~~~~~~~~~~~~~~{.php}
         * $conf = AsmConfig::getConfig();
         * $host = $conf->mysql->zabbix->host;
         * ~~~~~~~~~~~~~~
         */
        static function getConfig() {
            $reader = new Yaml();
            $configArray = $reader->fromFile($this->filename);
            $config = new Config($configArray);
            return $config;
        }

        public function setConfig($config) {
            # code...
        }

        /**
         * @brief  Permet de récupérer la configuration sous format JSON
         * @return string configuration sous format JSON
         */
        static public function getJsonConfig () {

            $reader = new Yaml();
            $configArray = $reader->fromFile($this->filename);
            $json = json_encode($configArray);
            return $json;
        }

    }