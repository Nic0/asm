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
        public function dashboard () {
            if(isLogged()) {
                $zEvent = new ZabbixEvent();
                $gpliTickets = new GLPITicket();
                $badPassword = new BadPassword();

                $this->addData('zabbix', $zEvent->getLast())
                     ->addData('glpi',   $gpliTickets->getLast())
                     ->addData('badpasswd', $badPassword->getAll())
                     ->addData('glpi_due',   $gpliTickets->getLastOverDue())
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

        public function snmp_renater () {
            $renater = "10.0.72.1";
            $community = "public";
            $oidD = ".1.3.6.1.2.1.2.2.1.10.1";
            $oidU = ".1.3.6.1.2.1.2.2.1.16.1";
            $timeout =  1000000;

            $data = array(
                    "down" => snmp_get_value($renater, $community, $oidD, $timeout),
                    "up" => snmp_get_value($renater, $community, $oidU, $timeout)
            );
            $this->renderJson(json_encode($data));
        }

        public function snmp_adista () {
            $adista = "10.0.72.9";
            $community = "public";
            $oidD = ".1.3.6.1.2.1.2.2.1.10.1";
            $oidU = ".1.3.6.1.2.1.2.2.1.16.1";
            $timeout =  1000000;

            $data = array(
                    "down" => snmp_get_value($adista, $community, $oidD, $timeout),
                    "up" => snmp_get_value($adista, $community, $oidU, $timeout)
            );
            $this->renderJson(json_encode($data));
        }
    }