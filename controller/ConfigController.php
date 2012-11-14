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
            if (isAjax()) {
                $json = AsmConfig::getJsonConfig();
                $this->renderJson($json);
            }
        }

        public function setup () {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $data['config'] = AsmConfig::getConfig();
            } else {
                debug($_POST, 'post');
                AsmConfig::setConfig($_POST);
            }

            $this->render($data);
        }

        public function ajax_dbglpi () {
            if (isAjax()) {
                $data['config'] = AsmConfig::getConfig();
                $this->render($data);
            }
        }

        public function ajax_dbzabbix () {
            if (isAjax()) {
                $data['config'] = AsmConfig::getConfig();
                $this->render($data);
            }
        }

    }