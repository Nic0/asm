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

        /**
         * @brief Point d'entrée de la page de configuration
         * @return None
         */
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

        /**
         * @brief Rendu AJAX pour la configuration de l'accès à la base de donnée
         * de GLPI
         * @return None
         */
        public function ajax_dbglpi () {
            if (isAjax()) {
                $this->render();
            }
        }

        /**
         * @brief Rendu AJAX pour la configuration de l'accès à la base de donnée
         * de Zabbix
         * @return None
         */
        public function ajax_dbzabbix () {
            if (isAjax()) {
                $this->render();
            }
        }

        /**
         * @brief Rendu AJAX pour le bouton de réinitialisation
         * @return [type] [description]
         */
        public function ajax_reset () {
            if (isAjax()) {
                $this->render();
            } elseif (isPost()) {
                if (isLogged()) {
                    AsmConfig::resetConfig();
                    $this->redirect('/config');
                }
            }
        }

    }