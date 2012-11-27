<?php

    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;
    use Zend\Db\Sql\Expression;

    class Snmp extends PhpAsmModel {

        public $id;
        public $ip;
        public $name;
        public $community;
        public $oid;
        public $warning;
        public $alert;
        public $value;

        public function save ($post) {
            $snmp = $this->createObjectFromSingleData($post);

            $this->sql = new Sql($this->adapter);
            $insert = $this->sql->insert();
            $insert->into('snmp')
                   ->columns(array('ip', 'community', 'name', 'oid', 'warning', 'alert'))
                   ->values(array(
                        'ip'        => $snmp->ip,
                        'community' => $snmp->community,
                        'name'      => $snmp->name,
                        'oid'       => $snmp->oid,
                        'warning'   => $snmp->warning,
                        'alert'     => $snmp->alert
                    ));

            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $statement->execute();
        }

        public function getStats () {
            $data = $this->getAll('snmp', 'Snmp');

            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from('snmp_input')
                   ->group('snmp_id')
                   ->where('unix_timestamp(date) > ' . (time() - (60*6)))
                   ->columns(array(new Expression('(MAX(value) - MIN(value))/(TIMESTAMPDIFF(SECOND, MIN(date), MAX(date))) as value'), 'snmp_id'));

            $result = $this->select($select);

            $r = array();
            foreach ($result as $row) {
                $r[$row->snmp_id] = $row->value;
            }

            foreach ($data as $snmp) {
                $snmp->value = $r[$snmp->id];
            }
        }

    }