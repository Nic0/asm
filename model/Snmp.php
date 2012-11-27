<?php

    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;

    class Snmp extends PhpAsmModel {

        public $id;
        public $ip;
        public $name;
        public $community;
        public $oid;
        public $warning;
        public $alert;

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

    }