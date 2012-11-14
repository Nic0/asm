<?php

    use Zend\Config\Reader\Yaml as YamlR;
    use Zend\Config\Writer\Yaml as YamlW;
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

        static $filename = '../config.yaml'; /** @brief Nom du fichier de config */

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
        static public function getConfig() {
            $reader = new YamlR();
            $configArray = $reader->fromFile(self::$filename);
            $config = new Config($configArray);
            return $config;
        }

        static public function setConfig($post) {
            $config = self::postToArray($post);
            //$reader = new YamlR();
            //$config = $reader->fromFile(self::$filename);

            //$writer = new YamlW();
            //echo $writer->toFile(self::$filename, $config);
        }

        /**
         * @brief  Permet de récupérer la configuration sous format JSON
         * @return string configuration sous format JSON
         */
        static public function getJsonConfig () {

            $reader = new YamlR();
            $configArray = $reader->fromFile(self::$filename);
            $json = json_encode($configArray);
            return $json;
        }

        static private function postToArray ($post) {
            $config = array();

            foreach ($post as $key => $value) {

                $pointeur = &$config;
                $path = explode('_', $key);

                for ($i=0; $i < sizeOf($path); $i++) {
                    if ($i == sizeOf($path)-1) {
                        $pointeur[$path[$i]] = $value;
                    } else {
                        if (!array_key_exists($path[$i], $pointeur)) {
                            $pointeur[$path[$i]] = array();
                        }
                        $pointeur = &$pointeur[$path[$i]];
                    }
                }
            }
            debug($config, 'postarray');

            return $config;
        }

    }