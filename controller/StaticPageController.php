<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixEvent.php';
    require_once '../model/GLPITicket.php';
    require_once '../model/BadPassword.php';

    /**
     * @brief Controlleur pour la page principale
     */
    class StaticPageController extends Controller {

        /**
         * @brief Page d'accueil "home" principale
         * @return None
         */
        public function home () {
            if(isLogged()) {
                $zEvent = new ZabbixEvent();
                $gpliTickets = new GLPITicket();
                $badPassword = new BadPassword();

                $this->addData('zabbix', $zEvent->getLast())
                     ->addData('glpi',   $gpliTickets->getLast())
                     ->addData('badpasswd', $badPassword->getAll())
                     ->render();
            } else {
                flash('Vous devez vous identifier', 'warning');
                $this->redirect('/login');
            }
        }

        /**
         * @brief Obtient le rendu pour Zabbix, utilisé pour un appel AJAX
         * @return None
         */
        public function updateZabbix () {
            if (isAjax()) {

                $zEvent = new ZabbixEvent();
                $this->addData('zabbix', $zEvent->getLast())
                     ->render();
            }
        }

        /**
         * @brief Obtient le rendu pour GLPI, utilisé pour un appel AJAX
         * @return None
         */
        public function updateGlpi () {
            if (isAjax()) {

                $gpliTickets = new GLPITicket();
                $this->addData('glpi', $gpliTickets->getLast())
                     ->render();
            }
        }

        public function updateBadpasswd () {
            if (isAjax()) {

            $badPassword = new BadPassword();
                $this->addData('badpasswd', $badPassword->getAll())
                     ->render();
            }
        }
    }