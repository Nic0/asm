<?php

    require_once '../lib/Controller.php';
    require_once '../model/Snmp.php';
    require_once '../model/SnmpInput.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;

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
            $snmp = new Snmp();
            $snmpList = $snmp->getAll('snmp', 'Snmp');

            $si = new SnmpInput();
            foreach ($snmpList as $snmp) {
                $si->save($snmp);
            }

            $this->purge();
        }

        private function purge () {
            $snmp = new SnmpInput();
            $snmp->purge();
        }

    }