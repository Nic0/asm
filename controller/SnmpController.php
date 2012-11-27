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

            foreach ($snmpList as $snmp) {
                $si = new SnmpInput();
                $si->save($snmp);
            }

            // TODO: purge requete
        }

    }