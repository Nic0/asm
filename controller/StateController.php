<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixHost.php';
    require_once '../model/ZabbixItem.php';
    require_once '../model/State.php';
    require_once '../model/DisplayState.php';

    class StateController extends Controller {


        public function add () {
            if(!isPost()) {
                $zHost = new ZabbixHost();

                $this->addData('host', $zHost->getAll());
                $this->render();
            } else {
                $state = new State();
                $state = $state->create($_POST);
                $state->save();
            }
        }

        public function view () {
            $state = new DisplayState();
            // $state = $state->getById('1');
            $state->getAll('foobar');
            $this->render();
        }


        public function ajax_item () {
            $hostid = $_POST['hostid'];
            $zItem = new ZabbixItem();
            $this->addData('item', $zItem->getByHost($hostid));
            $this->render();
        }

    }