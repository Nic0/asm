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

        public function delete ($id) {
            if (loginRole() == 'admin') {
                $snmp = new Snmp();
                $snmp->delete($id, 'snmp');
                flash('Élément SNMP supprimé', 'success');
                $this->redirect('/');
            } else {
                $this->notAllowed();
            }
        }

        public function update ($id) {
            if (loginRole() == 'admin') {
                if (isPost()) {
                    $snmp = new Snmp();
                    $snmp->update($id, $_POST);
                    $this->redirect('/');
                } else {
                    $snmp = new Snmp();
                    $this->addData('snmp', $snmp->getById($id, 'snmp'));
                    $this->render();
                }
            } else {
                $this->notAllowed();
            }
        }

    }