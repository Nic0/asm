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

            $zEvent = new ZabbixEvent();
            $this->data['zabbix'] = $zEvent->getLast();

            $gpliTickets = new GLPITicket();
            $this->data['glpi'] = $gpliTickets->getLast();

            $this->render();
        }

        /**
         * @brief Obtient le rendu pour Zabbix, utilisé pour un appel AJAX
         * @return None
         */
        public function updateZabbix () {
            if (isAjax()) {

                $zEvent = new ZabbixEvent();
                $this->data['zabbix'] = $zEvent->getLast();

                $this->render();
            }
        }

        /**
         * @brief Obtient le rendu pour GLPI, utilisé pour un appel AJAX
         * @return None
         */
        public function updateGlpi () {
            if (isAjax()) {

                $gpliTickets = new GLPITicket();
                $this->data['glpi'] = $gpliTickets->getLast();

                $this->render();
            }
        }
    }