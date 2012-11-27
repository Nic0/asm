<?php

    require_once '../lib/Controller.php';
    require_once '../model/Snmp.php';
    require_once '../model/SnmpInput.php';

    class SnmpController extends Controller {


        public function add () {
            if(isPost()) {
                $snmp = new Snmp();
                $snmp->save($_POST);
                flash('Élément SNMP rajouté', 'success');
                $this->redirect('/');
            }
        }

        public function feed () {

        }
    }