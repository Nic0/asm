<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixEvent.php';
    class StaticPageController extends Controller {

        public function home () {
            $zEvent = new ZabbixEvent();
            $data = array('zabbix' => $zEvent->getLast());

            $data['config'] = AsmConfig::getConfig();

            $this->render($data);
        }
    }