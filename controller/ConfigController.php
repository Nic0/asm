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
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $data['config'] = AsmConfig::getConfig();
                $this->render($data);
            } else {
                debug($_POST, 'post');
                AsmConfig::setConfig($_POST);
                die;
            }
        }

        public function ajax_dbglpi () {
            $data['config'] = AsmConfig::getConfig();
            $this->render($data);
        }
    }