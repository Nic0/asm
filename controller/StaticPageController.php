<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixEvent.php';
    require_once '../model/GLPITicket.php';

    class StaticPageController extends Controller {

        public function home () {
            $data = array();

            $zEvent = new ZabbixEvent();
            $data['zabbix'] = $zEvent->getLast();

            $gpliTickets = new GLPITicket();
            $data['glpi'] = $gpliTickets->getLast();

            $data['config'] = AsmConfig::getConfig();

            $this->render($data);
        }

        public function updateZabbix () {
            $data = array();

            $zEvent = new ZabbixEvent();
            $data['zabbix'] = $zEvent->getLast();

            $data['config'] = AsmConfig::getConfig();

            $this->render($data);
        }

        public function updateGlpi () {
            $data = array();

            $gpliTickets = new GLPITicket();
            $data['glpi'] = $gpliTickets->getLast();

            $data['config'] = AsmConfig::getConfig();

            $this->render($data);
        }
    }