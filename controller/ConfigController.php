<?php

    require_once '../lib/Controller.php';
    require_once '../lib/AsmConfig.php';

    /**
     * @brief Controlleur pour la configuration
     */
    class ConfigController extends Controller {

        /**
         * @brief Récupère la configuration sous forme de JSON
         * @return None
         */
        public function getJsonConfig () {
            $json = AsmConfig::getJsonConfig();
            $this->renderJson($json);
        }

        public function setup () {
            $data['config'] = AsmConfig::getConfig();
            $this->render($data);
        }

        public function dbglpi () {
            $data['config'] = AsmConfig::getConfig();
            $this->render($data);
        }

    }