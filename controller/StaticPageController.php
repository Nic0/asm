<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixEvent.php';
    require_once '../model/GLPITicket.php';

    /**
     * @brief Controlleur pour la page principale
     */
    class StaticPageController extends Controller {

        /**
         * @brief Page d'accueil "home" principale
         * @return None
         */
        public function home () {
            $data = array();

            $zEvent = new ZabbixEvent();
            $data['zabbix'] = $zEvent->getLast();

            $gpliTickets = new GLPITicket();
            $data['glpi'] = $gpliTickets->getLast();

            $data['config'] = AsmConfig::getConfig();

            $this->render($data);
        }

        /**
         * @brief Obtient le rendu pour Zabbix, utilisé pour un appel AJAX
         * @return None
         */
        public function updateZabbix () {
            $data = array();

            $zEvent = new ZabbixEvent();
            $data['zabbix'] = $zEvent->getLast();

            $data['config'] = AsmConfig::getConfig();

            $this->render($data);
        }

        /**
         * @brief Obtient le rendu pour GLPI, utilisé pour un appel AJAX
         * @return None
         */
        public function updateGlpi () {
            $data = array();

            $gpliTickets = new GLPITicket();
            $data['glpi'] = $gpliTickets->getLast();

            $data['config'] = AsmConfig::getConfig();

            $this->render($data);
        }
    }