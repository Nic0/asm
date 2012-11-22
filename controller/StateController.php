<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixHost.php';
    require_once '../model/ZabbixItem.php';

    class StateController extends Controller {


        public function add () {
            if(!isPost()) {
                $zHost = new ZabbixHost();

                $this->addData('host', $zHost->getAll());
                $this->render();
            } else {
                var_dump($_POST);
            }
        }

        public function view () {
            $this->render();
        }


        public function ajax_item () {
            $hostid = $_POST['hostid'];
            $zItem = new ZabbixItem();
            $this->addData('item', $zItem->getByHost($hostid));
            $this->render();
        }

    }