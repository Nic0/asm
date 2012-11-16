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
            if (isLogged()) {
                if (isPost()) {
                    debug($_POST, 'post');
                    AsmConfig::setConfig($_POST);
                    $this->redirect('/config');
                }

                $this->render();
            } else {
                flash('Vous devez vous identifier', 'warning');
                $this->redirect('/login');
            }
        }

        public function ajax_dbglpi () {
            if (isAjax()) {
                $this->render();
            }
        }

        public function ajax_dbzabbix () {
            if (isAjax()) {
                $this->render();
            }
        }

        public function ajax_reset () {
            if (isAjax()) {
                $this->render();
            } elseif (isPost()) {
                AsmConfig::resetConfig();
                $this->redirect('/config');
            }
        }

    }