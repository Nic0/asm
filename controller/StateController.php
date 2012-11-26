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
                flash("L'ajout à été effectué");
                $this->redirect('/');
            }
        }

        public function view () {
            $state = new DisplayState();
            $this->addData('zabbix', $state->getAll());
            $this->render();
        }


        public function ajax_item () {
            $hostid = $_POST['hostid'];
            $zItem = new ZabbixItem();
            $this->addData('item', $zItem->getByHost($hostid));
            $this->render();
        }

        public function del ($id) {
            if (loginRole() == 'admin') {
                $state = new State();
                $state->del($id);
                flash("La suppression à été effectué");
                $this->redirect('/state/view');
            } else {
                flash("Vous n'avez pas les droits requis pour cette action");
                $this->redirect('/login');
            }
        }

        public function update ($id) {
            # code...
        }
    }