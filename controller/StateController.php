<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixHost.php';
    require_once '../model/ZabbixItem.php';
    require_once '../model/State.php';
    require_once '../model/DisplayState.php';
    require_once '../model/GLPIStat.php';

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
                flash("L'ajout à été effectué", 'success');
                $this->redirect('/');
            }
        }

        public function view () {
            $state = new DisplayState();
            $glpi = new GLPIStat();
            $snmp = new Snmp();

            $this->addData('zabbix', $state->getAll('states', 'DisplayState'))
                 ->addData('glpi', $glpi->getStats())
                 ->addData('snmp', $snmp->getStats())
                 ->render();
        }


        public function ajax_item () {
            $hostid = $_POST['hostid'];
            $zItem = new ZabbixItem();
            $this->addData('item', $zItem->getByHost($hostid));
            $this->render();
        }

        public function delete ($id) {
            if (loginRole() == 'admin') {
                $state = new State();
                $state->delete($id, 'states');
                flash("La suppression à été effectué", 'success');
                $this->redirect('/');
            } else {
                $this->notAllowed();
            }
        }

        public function update ($id) {
            if (loginRole() == 'admin') {
                if (!isPost()) {
                    $state = new State();
                    $this->addData('state', $state->getById($id));
                    $this->render();
                } else {
                    $state = new State();
                    $state->update($id, $_POST);
                    flash("La mise à jour à été effectué", "success");
                    $this->redirect('/');
                }
            } else {
                $this->notAllowed();
            }
        }
    }