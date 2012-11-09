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
    }