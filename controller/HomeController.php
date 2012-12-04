<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixEvent.php';
    require_once '../model/GLPITicket.php';
    require_once '../model/BadPassword.php';

    /**
     * @brief Controlleur pour la page principale
     */
    class HomeController extends Controller {

        /**
         * @brief Affichage de la page d'accueil
         * @return None
         */
        public function home () {
            $state = new DisplayState();
            $snmp = new Snmp();

            $this->addData('zabbix', $state->getAll())
                 ->addData('snmp', $snmp->getStats())
                 ->render();
        }

        public function ajax_glpi_stats () {
            $glpi = new GLPIStat();
            $this->addData('glpi', $glpi->getStats())
                 ->renderJson();
        }

        public function ajax_init_snmp () {
            $snmp = new SnmpInput();
            $this->addData('up', $snmp->getLast(3))
                 ->addData('down', $snmp->getLast(4))
                 ->renderJson();

        }
    }